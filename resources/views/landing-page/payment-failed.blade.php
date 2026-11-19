<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment not completed</title>
</head>
<body style="font-family:system-ui,sans-serif;text-align:center;padding:60px 20px;background:#f9ead9;color:#2b1b14">
    <h1>Payment complete nahi hua</h1>
    <p>Agar aapke account se paise kat gaye hain, to neeche diye reference number ke saath hamari support team se sampark karein.</p>
    @if ($txnid)
        <p>Reference: <strong>{{ $txnid }}</strong></p>
    @endif
    {{-- href mein apne landing page ka URL daalein --}}
    <a href="/" style="display:inline-block;margin-top:16px;padding:14px 28px;border-radius:999px;background:#bf4a14;color:#fff;text-decoration:none;font-weight:700">Dobara try karein</a>
</body>
</html>