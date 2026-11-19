<?php

namespace App\Http\Controllers\LandingPage;

use App\Models\PaymentOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class PayuController extends Controller
{
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-zA-Z\s.\'-]+$/',
            ],
            'email' => 'required|email|max:255',
            'mobile' => [
                'required',
                'regex:/^[6-9][0-9]{9}$/',
            ],
            'dob' => 'required|date|before_or_equal:today',
            'gender' => 'required|in:Male,Female,Other',
            'amount' => 'required'
        ]);

        $order = DB::transaction(function () use ($validated) {
            return PaymentOrder::create([
                'txnid' => 'TXN'.Str::upper(Str::random(20)),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'mobile' => $validated['mobile'],
                'dob' => $validated['dob'],
                'gender' => $validated['gender'],

                // Never accept amount from frontend.
                'amount' => $validated['amount'],

                'status' => 'pending',
            ]);
        });

        $key = config('services.payu.key');
        $salt = config('services.payu.salt');

        $amount = number_format(
            (float) $order->amount,
            2,
            '.',
            ''
        );

        $firstname = Str::before($order->name, ' ');
        $lastname = trim(Str::after($order->name, ' '));

        $productinfo = 'OS0000NR';

        $params = [
            'key' => $key,
            'txnid' => $order->txnid,
            'amount' => $amount,
            'productinfo' => $productinfo,
            'firstname' => $firstname,
            'email' => $order->email,
            'phone' => $order->mobile,
            'lastname' => $lastname,
            'surl' => route('payu.callback'),
            'furl' => route('payu.callback'),
        ];

        // Five empty UDF fields are retained.
        $hashString = implode('|', array_merge(
    [
        $params['key'],
        $params['txnid'],
        $params['amount'],
        $params['productinfo'],
        $params['firstname'],
        $params['email'],
    ],
    array_fill(0, 10, ''),
    [$salt]
));

        $params['hash'] = strtolower(
            hash('sha512', $hashString)
        );

        $params['udf1'] = '';
        $params['udf2'] = '';
        $params['udf3'] = '';
        $params['udf4'] = '';
        $params['udf5'] = '';

        return response()->json([
            'success' => true,
            'payment' => $params,
            'order_id' => $order->id,
        ]);
    }


        public function callback(Request $request)
    {
        $data  = $request->except('_token');
        $txnid = $data['txnid'] ?? null;

        // 1. Hash check: bina sahi hash ke response ko trust nahi karna
        if (! $txnid || ! $this->verifyResponseHash($data)) {
            Log::warning('PayU callback: hash mismatch / txnid missing', $data);

            return redirect()->route('payu.failed', ['txnid' => $txnid]);
        }

        $payuStatus = strtolower($data['status'] ?? '');

        // 2. Order update (lock taaki double POST se do baar process na ho)
        $order = DB::transaction(function () use ($txnid, $data, $payuStatus) {
            $order = PaymentOrder::where('txnid', $txnid)->lockForUpdate()->first();

            if (! $order) {
                return null;
            }

            // Pehle se success hai to dobara overwrite mat karo
            if ($order->status === 'success') {
                return $order;
            }

            $amountOk = round((float) ($data['amount'] ?? 0), 2) === round((float) $order->amount, 2);

            if ($payuStatus === 'success' && $amountOk) {
                $status = 'success';
            } elseif ($payuStatus === 'pending') {
                $status = 'pending';
            } else {
                $status = 'failed';
            }

            $order->update([
                'status'        => $status,
                'mihpayid'      => $data['mihpayid'] ?? null,
                'payu_status'   => $data['status'] ?? null,
                'payment_mode'  => $data['mode'] ?? null,
                'bank_ref_num'  => $data['bank_ref_num'] ?? null,
                'error_message' => $amountOk
                    ? Str::limit($data['error_Message'] ?? '', 250, '')
                    : 'Amount mismatch',
                'payu_response' => $data,
                'paid_at'       => $status === 'success' ? now() : null,
            ]);

            return $order;
        });

        if (! $order) {
            Log::warning('PayU callback: order not found', ['txnid' => $txnid]);

            return redirect()->route('payu.failed', ['txnid' => $txnid]);
        }

        // 3. Redirect
        if ($order->status === 'success') {
            return redirect()->route('payu.thankyou', $order->txnid);
        }

        return redirect()->route('payu.failed', ['txnid' => $order->txnid]);
    }

    public function thankyou(string $txnid)
    {
        $order = PaymentOrder::where('txnid', $txnid)
            ->where('status', 'success')
            ->firstOrFail();

        // Apne thank-you blade ka naam yahan daalein
        return view('landing-page.name-report.thankyou', [
            'orderinfo' => $order
        ]);
    }

    public function failed(Request $request)
    {
        return view('landing-page.payment-failed', [
            'txnid' => $request->query('txnid'),
        ]);
    }

    /**
     * PayU reverse hash:
     * sha512(SALT|status||||||udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key)
     * additionalCharges aaye to: sha512(additionalCharges|SALT|status|...)
     */
    private function verifyResponseHash(array $data): bool
    {
        if (empty($data['hash'])) {
            return false;
        }

        $parts = [
            config('services.payu.salt'),
            $data['status'] ?? '',
            '', '', '', '', '',
            $data['udf5'] ?? '',
            $data['udf4'] ?? '',
            $data['udf3'] ?? '',
            $data['udf2'] ?? '',
            $data['udf1'] ?? '',
            $data['email'] ?? '',
            $data['firstname'] ?? '',
            $data['productinfo'] ?? '',
            $data['amount'] ?? '',
            $data['txnid'] ?? '',
            config('services.payu.key'),
        ];

        if (! empty($data['additionalCharges'])) {
            array_unshift($parts, $data['additionalCharges']);
        }

        $expected = strtolower(hash('sha512', implode('|', $parts)));

        return hash_equals($expected, strtolower($data['hash']));
    }
}