@php
    // Support contact: sirf asli details daalein. Khali chhodenge to "Need help?" block hide rahega.
    $supportPhone = ''; // e.g. '+91 99999 99999'
    $supportEmail = ''; // e.g. 'support@example.com'

    $firstName = \Illuminate\Support\Str::before(trim($orderinfo->name), ' ');
    $dobText = $orderinfo->dob ? \Illuminate\Support\Carbon::parse($orderinfo->dob)->format('d M Y') : '';
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Payment Successful | Name Numerology Report</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#bf4a14">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Serif+Devanagari:wght@600&family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Hanken+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --ink: #2b1b14;
            --muted: #7b6657;
            --cream: #f9ead9;
            --paper: #fffaf4;
            --orange: #cf6a2e;
            --deep: #bf4a14;
            --deep-d: #8c3409;
            --red: #a8281f;
            --green: #237a37;
            --line: #ecd2b6;
            --r-lg: 20px;
            --r-md: 12px;
            --r-sm: 8px;
            --serif: "Fraunces", Georgia, serif;
            --sans: "Hanken Grotesk", system-ui, -apple-system, "Segoe UI", sans-serif;
            --gutter: clamp(16px, 4vw, 32px);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box
        }

        html {
            -webkit-text-size-adjust: 100%
        }

        body {
            margin: 0;
            font-family: var(--sans);
            font-size: 17px;
            line-height: 1.6;
            color: var(--ink);
            background: var(--cream);
            overflow-x: hidden
        }

        h1,
        h2,
        h3 {
            font-family: var(--serif);
            line-height: 1.15;
            margin: 0 0 .5em;
            letter-spacing: -.01em;
            overflow-wrap: break-word
        }

        h1 {
            font-size: clamp(2rem, 6vw, 3rem);
            font-weight: 800
        }

        h2 {
            font-size: clamp(1.35rem, 3.6vw, 1.8rem);
            font-weight: 800
        }

        h3 {
            font-size: 1.08rem;
            font-weight: 600;
            margin-bottom: .2em
        }

        p {
            margin: 0 0 1em
        }

        a {
            color: inherit
        }

        :focus-visible {
            outline: 3px solid var(--ink);
            outline-offset: 3px
        }

        .wrap {
            width: 100%;
            max-width: 720px;
            margin-inline: auto;
            padding-inline: var(--gutter)
        }

        /* header */
        .site-head {
            background: var(--paper);
            border-bottom: 1px solid var(--line)
        }

        .site-head .wrap {
            max-width: 1160px;
            padding-block: 14px
        }

        .brand {
            font: 800 1.35rem var(--serif);
            color: var(--deep);
            white-space: nowrap
        }

        .om {
            font-family: "Noto Serif Devanagari", var(--serif);
            margin-right: 8px;
            font-size: 1.25rem;
            vertical-align: -1px
        }

        .toran {
            height: 16px;
            background:
                linear-gradient(135deg, var(--deep) 25%, transparent 25%) -8px 0/16px 16px,
                linear-gradient(225deg, var(--deep) 25%, transparent 25%) -8px 0/16px 16px
        }

        /* hero */
        .thanks {
            background: linear-gradient(160deg, #f7dec6 0%, #eec3a0 100%);
            padding: clamp(32px, 6vw, 56px) 0 clamp(40px, 7vw, 64px)
        }

        .card {
            background: var(--paper);
            border-radius: var(--r-lg);
            padding: clamp(22px, 5vw, 36px);
            box-shadow: 0 20px 50px rgba(140, 52, 9, .22)
        }

        .hero-card {
            text-align: center
        }

        .check {
            width: 88px;
            height: 88px;
            margin: 0 auto 14px;
            display: block
        }

        .check circle {
            fill: #e6f6ea;
            stroke: #2f9e44;
            stroke-width: 4;
            stroke-dasharray: 260;
            stroke-dashoffset: 260;
            animation: draw .6s ease-out forwards
        }

        .check path {
            fill: none;
            stroke: #2f9e44;
            stroke-width: 6;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 60;
            stroke-dashoffset: 60;
            animation: draw .35s .5s ease-out forwards
        }

        @keyframes draw {
            to {
                stroke-dashoffset: 0
            }
        }

        .kicker {
            font: 600 1rem/1.4 "Noto Serif Devanagari", var(--serif);
            color: var(--deep);
            letter-spacing: .02em;
            margin: 0 0 8px
        }

        .lede {
            color: var(--muted);
            max-width: 46ch;
            margin-inline: auto
        }

        .delivery {
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: left;
            background: #fcefdf;
            border: 1.5px dashed var(--orange);
            border-radius: var(--r-md);
            padding: 14px 16px;
            margin: 6px 0 24px;
            font-weight: 600
        }

        .delivery .ico {
            flex: none;
            display: grid;
            place-items: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #f8dcc4;
            color: var(--deep)
        }

        .delivery span small {
            display: block;
            font-weight: 400;
            color: var(--muted);
            font-size: .9rem
        }

        svg.i {
            width: 22px;
            height: 22px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round
        }

        /* order summary */
        .summary {
            margin: 0;
            text-align: left;
            border: 1px solid var(--line);
            border-radius: var(--r-md);
            overflow: hidden
        }

        .summary div {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 2px 16px;
            padding: 11px 16px;
            border-bottom: 1px solid var(--line)
        }

        .summary div:last-child {
            border-bottom: 0
        }

        .summary div:nth-child(odd) {
            background: #fffdf9
        }

        .summary dt {
            color: var(--muted);
            font-size: .95rem;
            flex: none
        }

        .summary dd {
            margin: 0 0 0 auto;
            font-weight: 600;
            text-align: right;
            overflow-wrap: anywhere;
            min-width: 0
        }

        .summary .paid dd {
            font: 800 1.25rem var(--serif);
            color: var(--green)
        }

        .summary .ref dd {
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: .9rem;
            font-weight: 500
        }

        .note {
            font-size: .88rem;
            color: var(--muted);
            margin: 12px 0 0
        }

        /* next steps */
        .next {
            padding: clamp(40px, 7vw, 60px) 0 0
        }

        .steps {
            list-style: none;
            padding: 0;
            margin: 20px 0 0;
            display: grid;
            gap: 12px
        }

        .steps li {
            display: flex;
            gap: 14px;
            align-items: flex-start;
            background: var(--paper);
            border: 1px solid var(--line);
            border-left: 6px solid var(--orange);
            border-radius: var(--r-md);
            padding: 16px 18px
        }

        .steps .num {
            flex: none;
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--deep);
            color: #fff;
            font: 800 1rem var(--serif)
        }

        .steps p {
            margin: 0;
            color: var(--muted);
            font-size: .96rem
        }

        .tip {
            margin: 18px 0 0;
            padding: 14px 16px;
            border-radius: var(--r-md);
            background: #fbe0d2;
            font-size: .95rem
        }

        /* help */
        .help {
            margin-top: clamp(32px, 6vw, 48px);
            text-align: center
        }

        .help p {
            color: var(--muted)
        }

        .help-links {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5em;
            font: 700 1rem/1.1 var(--sans);
            padding: 14px 24px;
            border-radius: 999px;
            text-decoration: none;
            background: #fff;
            color: var(--deep);
            border: 1.5px solid var(--deep);
            transition: background .15s, color .15s
        }

        .btn:hover {
            background: var(--deep);
            color: #fff
        }

        footer {
            padding: clamp(36px, 6vw, 56px) 0 28px;
            font-size: .88rem;
            color: var(--muted);
            text-align: center
        }

        footer p {
            margin: .3em 0
        }

        footer a {
            margin: 0 8px
        }

        @media (max-width:480px) {
            body {
                font-size: 16px
            }

            .summary div {
                padding: 10px 12px
            }

            .summary dt {
                font-size: .88rem
            }
        }

        @media (prefers-reduced-motion:reduce) {

            .check circle,
            .check path {
                animation: none;
                stroke-dashoffset: 0
            }

            .btn {
                transition: none
            }
        }
    </style>

</head>

<body>
    <header class="site-head">
        <div class="wrap">
            <span class="brand"><span class="om" aria-hidden="true">ॐ</span>Occult Sciences</span>
        </div>
    </header>

    <main>
        <section class="thanks">
            <div class="wrap">
                <div class="card hero-card">
                    <svg class="check" viewBox="0 0 88 88" aria-hidden="true">
                        <circle cx="44" cy="44" r="40" />
                        <path d="M27 45l12 12 23-25" />
                    </svg>
                    <p class="kicker">॥ शुभ नाम · शुभ भाग्य ॥</p>
                    <h1>Payment successful!</h1>
                    <p class="lede">Thank you, {{ $firstName }}. Your Name Numerology Report is confirmed.</p>

                    <div class="delivery" role="status">
                        <span class="ico"><svg class="i" viewBox="0 0 24 24">
                                <path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7z" />
                                <path d="M14 2v5h5M9 13h6M9 17h6" />
                            </svg></span>
                        <span>Your report is on its way to your email and WhatsApp.
                            <small>It comes as a PDF, along with your free remedy guides.</small></span>
                    </div>

                    <dl class="summary">
                        <div>
                            <dt>Report</dt>
                            <dd>Name Numerology Report</dd>
                        </div>
                        <div>
                            <dt>Name</dt>
                            <dd>{{ $orderinfo->name }}</dd>
                        </div>
                        @if ($dobText)
                            <div>
                                <dt>Date of birth</dt>
                                <dd>{{ $dobText }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt>Email</dt>
                            <dd>{{ $orderinfo->email }}</dd>
                        </div>
                        <div>
                            <dt>WhatsApp</dt>
                            <dd>+91 {{ $orderinfo->mobile }}</dd>
                        </div>
                        <div class="paid">
                            <dt>Amount paid</dt>
                            <dd>₹{{ number_format((float) $orderinfo->amount, 2) }}</dd>
                        </div>
                        @if ($orderinfo->mihpayid)
                            <div class="ref">
                                <dt>Payment ID</dt>
                                <dd>{{ $orderinfo->mihpayid }}</dd>
                            </div>
                        @endif
                        <div class="ref">
                            <dt>Order reference</dt>
                            <dd>{{ $orderinfo->txnid }}</dd>
                        </div>
                    </dl>
                    <p class="note">Please keep your order reference for any future queries.</p>
                </div>
                <div style="display: flex; justify-content: center;margin-top:10px;">
                    {{-- <a href="{{ route('api/name-report/generate-report/'.$reportinfo->id) }}" class="btn sm js-open" type="button">Download Report</a> --}}
                </div>
            </div>
        </section>

        <div class="toran" aria-hidden="true"></div>

        <div class="wrap">
            <section class="next">
                <h2>What happens next</h2>
                <ol class="steps">
                    <li>
                        <span class="num">1</span>
                        <div>
                            <h3>Your report is prepared</h3>
                            <p>It is built from the name, date of birth and gender you entered.</p>
                        </div>
                    </li>
                    <li>
                        <span class="num">2</span>
                        <div>
                            <h3>It arrives as a PDF</h3>
                            <p>On your email and WhatsApp, together with your free remedy guides.</p>
                        </div>
                    </li>
                    <li>
                        <span class="num">3</span>
                        <div>
                            <h3>Start with one remedy</h3>
                            <p>Pick one simple remedy from the report and begin today.</p>
                        </div>
                    </li>
                </ol>
                <p class="tip"><strong>Tip:</strong> Can't find the email? Check your Spam and Promotions folders.</p>
            </section>

            @if ($supportPhone || $supportEmail)
                <section class="card help">
                    <h2>Need help?</h2>
                    <p>If your report hasn't arrived or a detail above is wrong, contact us with your order reference.</p>
                    <div class="help-links">
                        @if ($supportPhone)
                            <a class="btn" href="tel:{{ preg_replace('/\s+/', '', $supportPhone) }}">{{ $supportPhone }}</a>
                        @endif
                        @if ($supportEmail)
                            <a class="btn"
                                href="mailto:{{ $supportEmail }}?subject={{ rawurlencode('Order ' . $orderinfo->txnid) }}">{{ $supportEmail }}</a>
                        @endif
                    </div>
                </section>
            @endif
        </div>
    </main>

    <footer>
        <div class="wrap">
            <p>© {{ date('Y') }} Occult Sciences. All rights reserved.</p>
            {{-- <p><a href="/privacy-policy">Privacy</a><a href="/terms">Terms</a><a href="/refund-policy">Refunds</a></p> --}}
        </div>
    </footer>
</body>

</html>