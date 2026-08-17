<x-mail::message>
<div style="text-align:center; padding:20px 0;">
    <div style="font-size:50px;">🙏</div>
    <h1 style="color:#6b2d00; margin-bottom:10px;">
        Thank You for Joining CosmicVibe
    </h1>
    <p style="font-size:18px; color:#8a4b00; letter-spacing:2px;">
        ✨ YOUR COSMIC JOURNEY BEGINS HERE ✨
    </p>
</div>
<p style="font-size:16px; color:#5d3b00;">
Hello <strong>{{ $user->name ?? 'User' }}</strong>,
</p>
<p style="font-size:16px; color:#5d3b00; line-height:1.8;">
Thank you for becoming a part of <strong>CosmicVibe</strong>.
We're delighted to welcome you to a community dedicated to astrology,
numerology, and spiritual guidance.
</p>

<p style="font-size:16px; color:#5d3b00; line-height:1.8;">
Your account has been successfully created, and you can now explore
personalized reports, cosmic insights, numerology calculations, and much more.
</p>

<x-mail::panel>
<div style="text-align:center;">
    <h2 style="margin:0; color:#8a4b00;">🌟 Welcome to CosmicVibe 🌟</h2>
    <p style="margin-top:10px; color:#6b2d00;">
        Astrology • Numerology • Cosmic Guidance
    </p>
</div>
</x-mail::panel>

<x-mail::button :url="config('app.url')" color="warning">
Explore Your Dashboard
</x-mail::button>

<p style="font-size:16px; color:#5d3b00; line-height:1.8;">
We're excited to accompany you on your journey of self-discovery and
cosmic wisdom.
</p>

<p style="font-size:16px; color:#5d3b00;">
With gratitude,<br>
<strong>The CosmicVibe Team</strong>
</p>

</x-mail::message>