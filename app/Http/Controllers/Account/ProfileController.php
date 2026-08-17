<?php

namespace App\Http\Controllers\Account;

use App\Enums\Message;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAddress;
use App\Services\flashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public $flasherService;

    function __construct(flashService $flasher)
    {
        $this->flasherService = $flasher;
    }
    function index()
    {
        $loginUserId = loginAccount()['account_id'];
        $user = User::find($loginUserId);
        return view('account.profile', compact('user'));
    }

    public function update(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'full_name'       => ['required', 'string', 'max:255'],
            'phone_code'      => ['required', 'string', 'digits_between:1,4'],
            'phone'      => ['required', 'string', 'max:20'],
            'address'    => ['nullable', 'string', 'max:500'],
            'country'    => ['nullable', 'integer'],
            'state'      => ['nullable', 'integer'],
            'city'       => ['nullable', 'integer'],
            'zipcode'    => ['nullable', 'string', 'max:20'],
            'profile'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $loginUserId = loginAccount()['account_id'];

        $user = User::find($loginUserId);
        $user->name = $validated['full_name'];
        $user->phone_code = $validated['phone_code'] ?? null;
        $user->phone = $validated['phone'] ?? null;
        if (!empty($validated['profile'] ?? '')) {
            $user->avatar = $validated['profile'] ?? null;
        }
        $user->save();


        UserAddress::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'address'    => $validated['address'] ?? null,
                'country_id' => $validated['country'] ?? null,
                'state_id'   => $validated['state'] ?? null,
                'city_id'    => $validated['city'] ?? null,
                'zipcode'    => $validated['zipcode'] ?? null,
            ]
        );


        $this->flasherService->successService(Message::PROFILEUPDATE->value);
        return redirect()
            ->back();
    }
}
