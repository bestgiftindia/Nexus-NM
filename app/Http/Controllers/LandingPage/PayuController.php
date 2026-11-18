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
                'amount' => config('services.payu.price'),

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

        $productinfo = 'Numerology Report';

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
            ['', '', '', '', ''],
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


    //// RESPONSE

    public function response(Request $request)
    {
        $data = $request->all();

        // Some SDK responses contain a nested response object.
        if (isset($data['response']) && is_array($data['response'])) {
            $data = $data['response'];
        }

        $result = $this->processPaymentResponse($data);

        return response()->json($result);
    }

    public function callback(Request $request)
    {
        // PayU may POST form data to surl/furl.
        $result = $this->processPaymentResponse(
            $request->all()
        );

        return redirect()->route(
            'payment.result',
            $result['order_id'] ?? 0
        );
    }

    private function processPaymentResponse(array $data): array
    {
        $txnid = $data['txnid'] ?? null;

        if (!$txnid) {
            return [
                'success' => false,
                'message' => 'Transaction ID missing.',
            ];
        }

        $order = PaymentOrder::where('txnid', $txnid)
            ->first();

        if (!$order) {
            return [
                'success' => false,
                'message' => 'Order not found.',
            ];
        }

        // Idempotency: never process an already-paid order again.
        if ($order->status === 'paid') {
            return [
                'success' => true,
                'order_id' => $order->id,
                'message' => 'Payment already verified.',
            ];
        }

        $key = config('services.payu.key');
        $salt = config('services.payu.salt');

        $expectedAmount = number_format(
            (float) $order->amount,
            2,
            '.',
            ''
        );

        // Verify important response parameters.
        if (
            ($data['key'] ?? '') !== $key ||
            ($data['amount'] ?? '') !== $expectedAmount
        ) {
            return [
                'success' => false,
                'order_id' => $order->id,
                'message' => 'Payment details mismatch.',
            ];
        }

        // Generate reverse hash.
        $status = strtolower($data['status'] ?? '');

        $reverseParts = [
            $salt,
            $status,
            '',
            '',
            '',
            '',
            '',
            $data['udf5'] ?? '',
            $data['udf4'] ?? '',
            $data['udf3'] ?? '',
            $data['udf2'] ?? '',
            $data['udf1'] ?? '',
            $data['email'] ?? '',
            $data['firstname'] ?? '',
            $data['productinfo'] ?? '',
            $data['amount'] ?? '',
            $txnid,
            $key,
        ];

        $reverseHashString = implode('|', $reverseParts);

        // Additional charges require a different reverse hash formula.
        if (!empty($data['additional_charges'])) {
            $reverseHashString =
                $data['additional_charges'].'|'.$reverseHashString;
        }

        $calculatedHash = strtolower(
            hash('sha512', $reverseHashString)
        );

        $receivedHash = strtolower($data['hash'] ?? '');

        if (
            !$receivedHash ||
            !hash_equals($calculatedHash, $receivedHash)
        ) {
            return [
                'success' => false,
                'order_id' => $order->id,
                'message' => 'Invalid payment response hash.',
            ];
        }

        // Verify customer and product details too.
        $expectedFirstName = \Illuminate\Support\Str::before(
            $order->name,
            ' '
        );

        if (
            ($data['email'] ?? '') !== $order->email ||
            ($data['firstname'] ?? '') !== $expectedFirstName ||
            ($data['productinfo'] ?? '') !== 'Numerology Report'
        ) {
            return [
                'success' => false,
                'order_id' => $order->id,
                'message' => 'Customer or product details mismatch.',
            ];
        }

        // Only a successful callback proceeds to API verification.
        if ($status !== 'success') {
            $order->update([
                'status' => $status === 'failure'
                    ? 'failed'
                    : 'pending',
                'payu_response' => $data,
            ]);

            return [
                'success' => false,
                'order_id' => $order->id,
                'message' => 'Payment not successful.',
            ];
        }

        return $this->verifyPaymentWithPayU($order, $data);
    }



    private function verifyPaymentWithPayU(
        PaymentOrder $order,
        array $callbackData
    ): array {

        $key = config('services.payu.key');
        $salt = config('services.payu.salt');

        $command = 'verify_payment';
        $txnid = $order->txnid;

        $hash = strtolower(hash(
            'sha512',
            $key.'|'.$command.'|'.$txnid.'|'.$salt
        ));

        $endpoint = config('services.payu.mode') === 'live'
            ? 'https://info.payu.in/merchant/postservice.php?form=2'
            : 'https://test.payu.in/merchant/postservice?form=2';

        try {
            $response = Http::asForm()
                ->timeout(30)
                ->post($endpoint, [
                    'key' => $key,
                    'command' => $command,
                    'var1' => $txnid,
                    'hash' => $hash,
                ]);

            if (!$response->successful()) {
                throw new \RuntimeException(
                    'PayU verification API unavailable.'
                );
            }

            $result = $response->json();

            $transaction = data_get(
                $result,
                'transaction_details.'.$txnid
            );

            if (!$transaction) {
                throw new \RuntimeException(
                    'Transaction details not found.'
                );
            }

            $verifiedAmount = $transaction['amt']
                ?? $transaction['transaction_amount']
                ?? null;

            $amountMatches = $verifiedAmount !== null
                && number_format((float) $verifiedAmount, 2, '.', '')
                    === number_format((float) $order->amount, 2, '.', '');

            $verified = strtolower(
                $transaction['status'] ?? ''
            ) === 'success';

            $mihpayid = $transaction['mihpayid'] ?? null;

            if (
                $verified &&
                $amountMatches &&
                $mihpayid &&
                (string) $mihpayid !== 'Not Found'
            ) {
                DB::transaction(function () use (
                    $order,
                    $transaction,
                    $callbackData
                ) {
                    $lockedOrder = PaymentOrder::whereKey($order->id)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($lockedOrder->status !== 'paid') {
                        $lockedOrder->update([
                            'status' => 'paid',
                            'mihpayid' => $transaction['mihpayid'],
                            'bank_ref_num' => $transaction['bank_ref_num'] ?? null,
                            'payu_response' => [
                                'callback' => $callbackData,
                                'verification' => $transaction,
                            ],
                        ]);
                    }
                });

                return [
                    'success' => true,
                    'order_id' => $order->id,
                    'redirect' => route('payment.result', $order->id),
                    'message' => 'Payment successfully verified.',
                ];
            }

            // Don't mark as failed solely because the verification
            // API returns pending or an amount mismatch.
            return [
                'success' => false,
                'order_id' => $order->id,
                'message' => 'Payment is awaiting final verification.',
            ];

        } catch (\Throwable $e) {

            report($e);

            return [
                'success' => false,
                'order_id' => $order->id,
                'message' => 'Unable to verify payment. Please try again.',
            ];
        }
    }


}