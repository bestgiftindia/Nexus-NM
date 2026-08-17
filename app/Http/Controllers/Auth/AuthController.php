<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\EmailRule;
use Illuminate\Http\Request;
use App\Services\flashService;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\OtpHistoryService;
use App\Enums\Message;
use App\Models\AdminLoginHistory;

class AuthController extends Controller
{
    public $flasherService;
    public $otpHistoryService;

    function __construct(flashService $flasher, OtpHistoryService $otpHistoryService)
    {
        $this->flasherService = $flasher;
        $this->otpHistoryService = $otpHistoryService;
    }
    function loginForm()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'exists:users,email', new EmailRule()],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email'    => 'Please enter a valid email address.',
            'email.exists'   => 'This email address is not registered.',
        ]);

        $user = User::where('email', $request->email)->first();
        $otp = $this->otpHistoryService->generate($user);
        session(['login_email' => $user->email]);
        $this->flasherService->successService(Message::OTPSEND->value);
        return redirect()->route('login.otp');
    }

    function otp_page()
    {
        return view('auth.login-otp');
    }

    function otp_verify(Request $request)
    {
        $otp = implode('', $request->otp);

        $request->merge([
            'otp' => $otp
        ]);
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = User::where('email', session('login_email'))->firstOrFail();

        if (!$this->otpHistoryService->checkOtp($user, $request->otp)) {
            return back()->withErrors([
                'otp' => Message::INVALIDOTP->value,
            ]);
        }

        Auth::login($user);
        session()->forget('login_email');
        $request->session()->regenerate();
        $this->loginHistory();
        $this->flasherService->successService(Message::OTPVERIFY->value);

        return redirect()->intended(route('account.dashboard'));
    }

    public function resendOtp(Request $request)
    {
        $email = session('login_email');

        $user = User::where('email', $email)->firstOrFail();

        $this->otpHistoryService->generate($user);

        $this->flasherService->successService(Message::OTPRESEND->value);
        session(['otp_resend_available_at' => now()->addSeconds(60)->timestamp]);
        return back();
    }

    public function logout(Request $request)
    {
        $sessionId = session()->getId();
        $loginUser = loginAccount();
        $this->logoutHistory($loginUser['account_id'], $sessionId);

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $this->flasherService->successService(Message::LOGOUT->value);

        return redirect()->route('login');
    }

    function registerForm()
    {
        return view('auth.register');
    }


    public function loginHistory()
    {
        $loginUser = loginAccount();
        AdminLoginHistory::create([
            'user_id'     => $loginUser['account_id'],
            'ip_address'   => request()->ip(),
            'user_agent'   => request()->userAgent(),
            'logged_in_at' => now(),
            'session_id'   => session()->getId(),
        ]);
    }

    public function logoutHistory($account_id, $sessionId)
    {
        AdminLoginHistory::user($account_id)
            ->where('session_id', $sessionId)
            ->whereNull('logged_out_at')
            ->update([
                'logged_out_at' => now(),
                'is_active'     => false
            ]);
    }
}
