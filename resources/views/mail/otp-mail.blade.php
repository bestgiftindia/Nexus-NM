<x-mail::message>
    <h3>Hello,</h3>
    <p>We received a request to verify your account associated with <strong>{{ config('app.name') }}</strong>.</p>
    <p>To complete the verification process, please use the One-Time Password (OTP) below:</p>
    <h2><strong>OTP: {{ $otp }}</strong></h2>
    <p>This verification code is valid for **10 minutes**.</p>
    <p>For your security:</p>
    <ul>
        <li>Never share your OTP with anyone.</li>
        <li>Our team will never ask for your OTP.</li>W
        <li>If you did not request this verification, you can safely ignore this email. Your account will remain
            secure.</li>
    </ul>
    <p>Need help? Feel free to contact our support team if you have any questions.</p>
    <p>Thanks,<br>
        <strong>{{ config('app.name') }} Team</strong>
    </p>
</x-mail::message>

