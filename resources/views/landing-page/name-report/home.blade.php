<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>Name Numerology Report | Find Out If Your Name Is Holding You Back</title>
    <meta name="description"
        content="A personalised name numerology report with spelling suggestions, lucky numbers, colours and simple remedies. Delivered instantly as a PDF.">
    <meta name="theme-color" content="#bf4a14">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Serif+Devanagari:wght@600&family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Hanken+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --ink: #2b1b14;
            --muted: #7b6657;
            --cream: #f9ead9;
            --paper: #fffaf4;
            --sun: #f0c9a6;
            --sun-2: #ffe2c8;
            --orange: #cf6a2e;
            --deep: #bf4a14;
            --deep-d: #8c3409;
            --red: #a8281f;
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
            scroll-behavior: smooth;
            -webkit-text-size-adjust: 100%
        }

        body {
            margin: 0;
            font-family: var(--sans);
            font-size: 17px;
            line-height: 1.6;
            color: var(--ink);
            background: var(--cream);
            overflow-x: hidden;
            padding-bottom: calc(76px + env(safe-area-inset-bottom, 0px))
        }

        img {
            max-width: 100%;
            display: block
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
            font-size: clamp(2rem, 6.2vw, 3.5rem);
            font-weight: 800
        }

        h2 {
            font-size: clamp(1.55rem, 4.4vw, 2.4rem);
            font-weight: 800
        }

        h3 {
            font-size: 1.15rem;
            font-weight: 600
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
            max-width: 1160px;
            margin-inline: auto;
            padding-inline: var(--gutter)
        }

        section {
            padding: clamp(48px, 8vw, 80px) 0
        }

        .lede {
            color: var(--muted);
            max-width: 60ch
        }

        .center {
            text-align: center
        }

        .center .lede {
            margin-inline: auto
        }

        /* buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5em;
            border: 0;
            cursor: pointer;
            font: 700 1.05rem/1.1 var(--sans);
            padding: 17px 30px;
            border-radius: 999px;
            background: var(--deep);
            color: #fff;
            text-decoration: none;
            transition: transform .15s, background .15s;
            box-shadow: 0 5px 0 var(--deep-d);
            text-align: center
        }

        .btn:hover {
            background: #d0581f
        }

        .btn:active {
            transform: translateY(3px);
            box-shadow: 0 2px 0 var(--deep-d)
        }

        .btn[disabled] {
            opacity: .7;
            cursor: wait
        }

        .btn.block {
            width: 100%
        }

        .btn.sm {
            padding: 11px 20px;
            font-size: .95rem;
            box-shadow: 0 3px 0 var(--deep-d)
        }

        /* header */
        .site-head {
            background: var(--paper);
            border-bottom: 1px solid var(--line)
        }

        .site-head .wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-block: 12px
        }

        .brand {
            font: 800 1.35rem var(--serif);
            color: var(--deep);
            text-decoration: none;
            white-space: nowrap
        }

        /* trust strip */
        .strip {
            background: #9c3d0d;
            color: #fde8d2;
            font-size: .9rem
        }

        .strip ul {
            list-style: none;
            margin: 0 auto;
            padding: 11px var(--gutter);
            max-width: 1160px;
            display: grid;
            grid-template-columns: repeat(4, auto);
            gap: 6px 32px;
            justify-content: center
        }

        .strip li {
            display: flex;
            align-items: flex-start;
            gap: 7px;
            line-height: 1.3
        }

        .strip li svg {
            color: #ffd8b8;
            font-size: 17px;
            margin-top: 1px
        }

        .kicker {
            font: 600 1.05rem/1.4 "Noto Serif Devanagari", var(--serif);
            color: var(--deep);
            letter-spacing: .02em;
            margin: 0 0 14px
        }

        .toran {
            height: 16px;
            background:
                linear-gradient(135deg, var(--deep) 25%, transparent 25%) -8px 0/16px 16px,
                linear-gradient(225deg, var(--deep) 25%, transparent 25%) -8px 0/16px 16px;
            background-color: transparent
        }

        .toran.on-dark {
            --tc: #9c3d0d
        }

        .om {
            font-family: "Noto Serif Devanagari", var(--serif);
            margin-right: 8px;
            font-size: 1.25rem;
            vertical-align: -1px
        }

        svg.i {
            width: 1em;
            height: 1em;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex: none
        }

        .ico {
            display: grid;
            place-items: center;
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: #f8dcc4;
            color: var(--deep);
            font-size: 24px;
            margin-bottom: 12px
        }

        .outcomes .ico {
            background: rgba(255, 255, 255, .18);
            color: #fff
        }

        .inside-grid .ico {
            background: #fbe8d6
        }

        /* hero */
        .hero {
            background: linear-gradient(160deg, #f7dec6 0%, #eec3a0 100%);
            padding: clamp(36px, 6vw, 64px) 0 clamp(48px, 7vw, 80px)
        }

        .hero .grid {
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr);
            gap: clamp(28px, 5vw, 56px);
            align-items: start
        }

        .hero .sub {
            font-size: clamp(1.05rem, 2.4vw, 1.2rem);
            color: #4a3426;
            max-width: 46ch
        }

        .tiles {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 24px 0 8px
        }

        .tile {
            width: 56px;
            text-align: center;
            background: #fff;
            border-radius: var(--r-md);
            padding: 8px 0 6px;
            box-shadow: 0 3px 0 rgba(0, 0, 0, .12)
        }

        .tile b {
            display: block;
            font: 800 1.6rem/1 var(--serif);
            color: var(--ink)
        }

        .tile span {
            font-size: .8rem;
            color: var(--muted)
        }

        .tile.hl {
            background: var(--deep)
        }

        .tile.hl b,
        .tile.hl span {
            color: #fff
        }

        .tiles-cap {
            font-size: .92rem;
            color: #5a4030;
            margin-bottom: 22px
        }

        .pills {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            padding: 0;
            list-style: none;
            margin: 0 0 26px
        }

        .pills li {
            background: rgba(255, 255, 255, .7);
            border-radius: 999px;
            padding: 6px 16px;
            font-size: .95rem;
            font-weight: 500
        }

        /* price block */
        .price {
            display: flex;
            align-items: center;
            gap: 8px 14px;
            flex-wrap: wrap
        }

        .price .now {
            font: 800 clamp(2.2rem, 6vw, 2.9rem)/1 var(--serif);
            color: var(--ink)
        }

        .price .was {
            text-decoration: line-through;
            color: #7a5a45;
            font-size: 1.25rem
        }

        .price .off {
            background: var(--red);
            color: #fff;
            font-size: .9rem;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 999px;
            white-space: nowrap
        }

        .limited {
            display: inline-block;
            margin-top: 12px;
            font-size: .92rem;
            font-weight: 700;
            color: var(--deep-d);
            background: #fff;
            border-radius: 999px;
            padding: 5px 14px
        }

        /* form card */
        .card {
            background: var(--paper);
            color: var(--ink);
            border-radius: var(--r-lg);
            padding: clamp(20px, 4vw, 30px);
            box-shadow: 0 20px 50px rgba(140, 52, 9, .28);
            min-width: 0
        }

        .card h2 {
            font-size: 1.45rem;
            margin-bottom: .2em
        }

        .sum {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            background: #fcefdf;
            border: 1px dashed var(--orange);
            border-radius: var(--r-md);
            padding: 12px 14px;
            margin: 0 0 14px
        }

        .sum b {
            display: block;
            font-size: .98rem
        }

        .sum span {
            font-size: .82rem;
            color: var(--muted)
        }

        .sum .sp {
            display: flex;
            align-items: baseline;
            gap: 6px;
            flex-wrap: wrap;
            justify-content: flex-end;
            text-align: right
        }

        .sum s {
            color: var(--muted);
            font-size: .9rem
        }

        .sum strong {
            font: 800 1.4rem var(--serif);
            color: var(--deep)
        }

        .sum em {
            font-style: normal;
            background: var(--red);
            color: #fff;
            font-size: .72rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px
        }

        #heroForm .sum {
            display: none
        }

        .pay-note {
            display: none;
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: var(--r-sm);
            background: #fbe0d2;
            color: var(--red);
            font-size: .85rem
        }

        .card .mini {
            color: var(--muted);
            font-size: .95rem;
            margin-bottom: 16px
        }

        .field {
            margin-bottom: 14px;
            min-width: 0
        }

        .field label,
        .legend {
            display: block;
            font-weight: 600;
            font-size: .92rem;
            margin-bottom: 5px
        }

        .field input,
        .field select {
            width: 100%;
            min-width: 0;
            font: inherit;
            font-size: 16px;
            padding: 13px 14px;
            border: 1.5px solid #e3c8aa;
            border-radius: var(--r-sm);
            background: #fff;
            color: var(--ink);
            -webkit-appearance: none;
            appearance: none
        }

        .field select {
            background-image: linear-gradient(45deg, transparent 50%, var(--muted) 50%), linear-gradient(135deg, var(--muted) 50%, transparent 50%);
            background-position: calc(100% - 20px) 55%, calc(100% - 14px) 55%;
            background-size: 6px 6px;
            background-repeat: no-repeat;
            padding-right: 36px
        }

        .field input[type=date] {
            min-height: 50px
        }

        .field input:focus,
        .field select:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(191, 74, 20, .22)
        }

        .field .err {
            display: none;
            color: var(--red);
            font-size: .85rem;
            margin-top: 4px
        }

        .field.bad input,
        .field.bad select {
            border-color: var(--red)
        }

        .field.bad .err {
            display: block
        }

        .two {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 12px
        }

        .addons {
            border: 1.5px dashed var(--orange);
            background: #fcefdf;
            border-radius: var(--r-md);
            padding: 12px 14px;
            margin: 6px 0 16px
        }

        .addons .legend {
            margin-bottom: 6px
        }

        .addon {
            display: flex;
            gap: 10px;
            align-items: center;
            padding: 6px 0;
            cursor: pointer;
            font-size: .97rem
        }

        .addon input {
            width: 20px;
            height: 20px;
            accent-color: var(--deep);
            flex: none
        }

        .addon span:last-child {
            margin-left: auto;
            font-weight: 700;
            white-space: nowrap
        }

        .total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            font-weight: 600;
            flex-wrap: wrap
        }

        .total .r {
            display: flex;
            align-items: baseline;
            gap: 8px;
            flex-wrap: wrap
        }

        .total s {
            color: var(--muted);
            font-weight: 400;
            font-size: .95rem
        }

        .total strong {
            font: 800 1.7rem var(--serif)
        }

        .total .off {
            background: var(--red);
            color: #fff;
            font: 700 .78rem var(--sans);
            padding: 3px 9px;
            border-radius: 999px
        }

        .safe {
            display: flex;
            justify-content: center;
            gap: 6px 18px;
            flex-wrap: wrap;
            margin-top: 12px;
            font-size: .85rem;
            color: var(--muted)
        }

        /* bonuses */
        .bonus-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 240px), 1fr));
            gap: 16px;
            margin-top: 34px;
            text-align: left
        }

        .bonus {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--r-md);
            padding: 20px;
            border-left: 6px solid var(--orange)
        }

        .bonus h3 {
            font-size: 1.05rem;
            margin-bottom: .3em
        }

        .bonus p {
            margin: 0;
            color: var(--muted);
            font-size: .95rem
        }

        .value {
            margin-top: 28px;
            display: inline-block;
            background: var(--deep);
            color: #fff;
            border-radius: 999px;
            padding: 12px 26px;
            font-weight: 600
        }

        .value b {
            color: #ffe2c8
        }

        /* inside */
        .inside {
            background: var(--paper)
        }

        .inside-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-top: 34px;
            border-top: 1px solid var(--line);
            border-left: 1px solid var(--line)
        }

        .inside-grid div {
            padding: 22px 20px;
            border-right: 1px solid var(--line);
            border-bottom: 1px solid var(--line)
        }

        .inside-grid h3 {
            margin-bottom: .3em
        }

        .inside-grid p {
            margin: 0;
            color: var(--muted);
            font-size: .95rem
        }

        /* problems -> solutions */
        .ps {
            display: grid;
            gap: 14px;
            margin-top: 34px
        }

        .ps-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 52px minmax(0, 1fr);
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--r-lg);
            overflow: hidden
        }

        .ps-row .p,
        .ps-row .s {
            padding: 20px 24px
        }

        .ps-row .p {
            background: #fbe0d2
        }

        .ps-row .s {
            background: #f8e5cd
        }

        .ps-row .mid {
            display: grid;
            place-items: center;
            background: var(--deep);
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700
        }

        .ps small {
            display: block;
            font-weight: 700;
            font-size: .8rem;
            margin-bottom: 6px
        }

        .ps .p small {
            color: var(--red)
        }

        .ps .s small {
            color: var(--deep-d)
        }

        .ps h3 {
            margin-bottom: .25em
        }

        .ps p {
            margin: 0;
            font-size: .97rem;
            color: #5a4538
        }

        /* outcomes */
        .outcomes {
            background: var(--deep);
            color: #fff
        }

        .outcomes h2 {
            color: #fff
        }

        .out-list {
            list-style: none;
            padding: 0;
            margin: 32px 0 0;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 6px 36px
        }

        .out-list li {
            padding: 16px 0;
            border-top: 1px solid rgba(255, 255, 255, .35)
        }

        .out-list b {
            display: block;
            font: 600 1.15rem var(--serif);
            margin-bottom: 2px
        }

        .out-list span {
            color: #ffe6d0;
            font-size: .96rem
        }

        /* who */
        .who {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(min(100%, 230px), 1fr));
            gap: 16px;
            margin-top: 34px
        }

        .who div {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--r-md);
            padding: 22px
        }

        .who h3 {
            margin-bottom: .2em
        }

        .who p {
            margin: 0;
            color: var(--muted);
            font-size: .96rem
        }

        /* offer */
        .offer {
            background: #efc5a0;
            padding: clamp(40px, 6vw, 60px) 0
        }

        .offer .wrap {
            display: flex;
            gap: 24px 32px;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap
        }

        .offer h2 {
            margin-bottom: .15em
        }

        .offer p {
            margin: 0
        }

        .offer .btn {
            background: var(--ink);
            box-shadow: 0 5px 0 #000
        }

        .offer .btn:hover {
            background: #4a2e20
        }

        /* about */
        .about {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: clamp(28px, 5vw, 56px);
            align-items: center
        }

        .about.solo {
            grid-template-columns: minmax(0, 720px)
        }

        .stats {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 14px
        }

        .stat {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--r-md);
            padding: 22px 14px;
            text-align: center
        }

        .stat b {
            display: block;
            font: 800 clamp(1.5rem, 4vw, 2rem) var(--serif);
            color: var(--deep)
        }

        .stat span {
            color: var(--muted);
            font-size: .92rem
        }

        /* reviews */
        .carousel {
            margin-top: 32px;
            text-align: left
        }

        .track {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            padding: 4px 2px 10px
        }

        .track::-webkit-scrollbar {
            display: none
        }

        .slide {
            flex: 0 0 calc((100% - 32px)/3);
            scroll-snap-align: start;
            margin: 0;
            min-width: 0;
            display: flex
        }

        .review {
            background: var(--cream);
            border: 1px solid var(--line);
            border-radius: var(--r-md);
            padding: 24px;
            display: flex;
            flex-direction: column;
            width: 100%
        }

        .review .stars {
            color: var(--orange);
            letter-spacing: 2px;
            margin-bottom: 8px
        }

        .review p {
            flex: 1
        }

        .who-row {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap
        }

        .review cite {
            font-style: normal;
            font-weight: 700;
            font-size: .95rem
        }

        .review cite span {
            display: block;
            font-weight: 400;
            color: var(--muted);
            font-size: .88rem
        }

        .vbadge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: .78rem;
            font-weight: 700;
            color: #237a37;
            background: #e6f6ea;
            border-radius: 999px;
            padding: 4px 10px;
            white-space: nowrap
        }

        .car-ctrl {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            margin-top: 14px
        }

        .car-btn {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 1.5px solid var(--deep);
            background: #fff;
            color: var(--deep);
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            display: grid;
            place-items: center;
            padding: 0 0 3px
        }

        .car-btn:hover {
            background: var(--deep);
            color: #fff
        }

        .dots {
            display: flex;
            gap: 8px
        }

        .dots button {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            border: 0;
            padding: 0;
            background: #e8c9a8;
            cursor: pointer
        }

        .dots button.on {
            background: var(--deep);
            width: 22px;
            border-radius: 6px
        }

        /* faq */
        .faq {
            max-width: 820px;
            margin: 34px auto 0
        }

        .faq details {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: var(--r-md);
            margin-bottom: 10px
        }

        .faq summary {
            cursor: pointer;
            list-style: none;
            padding: 17px 52px 17px 20px;
            font-weight: 600;
            position: relative
        }

        .faq summary::-webkit-details-marker {
            display: none
        }

        .faq summary::after {
            content: "+";
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
            color: var(--deep);
            transition: transform .2s
        }

        .faq details[open] summary::after {
            transform: translateY(-50%) rotate(45deg)
        }

        .faq details p {
            padding: 0 20px 18px;
            margin: 0;
            color: var(--muted)
        }

        /* final */
        .final {
            background: linear-gradient(160deg, #cf6a2e, #a93d0c);
            color: #fff
        }

        .final h2 {
            color: #fff
        }

        .final .lede {
            color: #ffe9d6
        }

        .final .price {
            justify-content: center;
            margin: 20px 0 22px
        }

        .final .price .now {
            color: #fff
        }

        .final .price .was {
            color: #ffd2b0
        }

        .final .btn {
            background: var(--ink);
            box-shadow: 0 5px 0 #000
        }

        .final .limited {
            margin: 0 0 22px
        }

        footer {
            padding: 28px 0;
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

        /* sticky bar */
        .sticky {
            position: fixed;
            inset: auto 0 0 0;
            background: var(--paper);
            border-top: 1px solid var(--line);
            padding: 10px 0 calc(10px + env(safe-area-inset-bottom, 0px));
            z-index: 40;
            box-shadow: 0 -8px 24px rgba(0, 0, 0, .1);
            transform: translateY(120%);
            transition: transform .25s
        }

        .sticky.show {
            transform: none
        }

        .sticky .wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px
        }

        .sticky .p {
            display: flex;
            align-items: baseline;
            gap: 8px;
            flex-wrap: wrap;
            font-weight: 700;
            min-width: 0
        }

        .sticky .p s {
            color: var(--muted);
            font-weight: 400;
            font-size: .95rem
        }

        .sticky .p b {
            font: 800 1.45rem var(--serif)
        }

        .sticky .p em {
            font-style: normal;
            background: var(--red);
            color: #fff;
            font-size: .75rem;
            padding: 2px 8px;
            border-radius: 999px
        }

        /* modal */
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(60, 22, 8, .7);
            display: none;
            align-items: flex-start;
            justify-content: center;
            padding: 16px;
            z-index: 60;
            overflow-y: auto
        }

        .modal.open {
            display: flex
        }

        .modal .card {
            width: min(460px, 100%);
            position: relative;
            margin: auto
        }

        .modal .card h2 {
            padding-right: 36px
        }

        .modal .x {
            position: absolute;
            right: 12px;
            top: 8px;
            border: 0;
            background: none;
            font-size: 1.9rem;
            line-height: 1;
            cursor: pointer;
            color: var(--muted);
            padding: 4px 8px
        }

        /* ---------- responsive ---------- */
        @media (max-width:960px) {
            .slide {
                flex-basis: calc((100% - 16px)/2)
            }

            .hero .grid,
            .about {
                grid-template-columns: minmax(0, 1fr)
            }

            .inside-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .out-list {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media (max-width:640px) {
            .slide {
                flex-basis: 88%
            }

            body {
                font-size: 16px
            }

            .strip ul {
                justify-content: flex-start;
                font-size: .85rem;
                gap: 2px 18px
            }

            .strip ul {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 6px 14px;
                font-size: .84rem;
                justify-content: stretch
            }

            .ps {
                gap: 12px
            }

            .ps-row {
                grid-template-columns: minmax(0, 1fr) 28px minmax(0, 1fr);
                border-radius: 16px
            }

            .ps-row .p,
            .ps-row .s {
                padding: 14px 12px
            }

            .ps-row .mid {
                font-size: 1rem
            }

            .ps h3 {
                font-size: .98rem
            }

            .ps p {
                font-size: .86rem;
                line-height: 1.45
            }

            .ps small {
                font-size: .7rem;
                letter-spacing: .04em;
                text-transform: uppercase
            }

            .inside-grid,
            .out-list {
                grid-template-columns: minmax(0, 1fr)
            }

            .two {
                grid-template-columns: minmax(0, 1fr)
            }

            .tile {
                width: 48px
            }

            .btn {
                padding: 16px 22px
            }

            .offer .wrap {
                flex-direction: column;
                align-items: flex-start
            }

            .offer .btn {
                width: 100%
            }

            .stats {
                gap: 10px
            }

            .sticky .btn {
                padding: 13px 18px;
                font-size: .95rem
            }
        }

        @media (max-width:380px) {
            .ps-row {
                grid-template-columns: minmax(0, 1fr) 24px minmax(0, 1fr)
            }

            .ps-row .p,
            .ps-row .s {
                padding: 12px 10px
            }

            .ps h3 {
                font-size: .92rem
            }

            .ps p {
                font-size: .82rem
            }

            .brand {
                font-size: 1.15rem
            }

            .btn.sm {
                padding: 10px 14px;
                font-size: .88rem
            }

            .sticky .p em {
                display: none
            }
        }

        @media (prefers-reduced-motion:reduce) {
            * {
                transition: none !important;
                scroll-behavior: auto !important
            }
        }
    </style>
</head>

<body>
    <svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
        <defs>
            <symbol id="i-rupee" viewBox="0 0 24 24">
                <path d="M6 3h12" />
                <path d="M6 8h12" />
                <path d="m6 13 8.5 8" />
                <path d="M6 13h3" />
                <path d="M9 13c6.667 0 6.667-10 0-10" />
            </symbol>
            <symbol id="i-briefcase" viewBox="0 0 24 24">
                <rect width="20" height="14" x="2" y="7" rx="2" />
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
            </symbol>
            <symbol id="i-grad" viewBox="0 0 24 24">
                <path d="M22 10 12 5 2 10l10 5 10-5z" />
                <path d="M6 12v5c3 3 9 3 12 0v-5" />
            </symbol>
            <symbol id="i-heart" viewBox="0 0 24 24">
                <path
                    d="M19 14c1.5-1.5 3-3.2 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.8 0-3 .5-4.5 2-1.5-1.5-2.7-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4 3 5.5l7 7z" />
            </symbol>
            <symbol id="i-shield" viewBox="0 0 24 24">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
            </symbol>
            <symbol id="i-star" viewBox="0 0 24 24">
                <path d="m12 3 2.7 5.8 6.3.8-4.6 4.4 1.2 6.3L12 17.2l-5.6 3.1 1.2-6.3L3 9.6l6.3-.8z" />
            </symbol>
            <symbol id="i-sun" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="4" />
                <path
                    d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4" />
            </symbol>
            <symbol id="i-lock" viewBox="0 0 24 24">
                <rect x="4" y="11" width="16" height="10" rx="2" />
                <path d="M8 11V7a4 4 0 0 1 8 0v4" />
            </symbol>
            <symbol id="i-unlock" viewBox="0 0 24 24">
                <rect x="4" y="11" width="16" height="10" rx="2" />
                <path d="M8 11V7a4 4 0 0 1 7.5-2" />
            </symbol>
            <symbol id="i-bolt" viewBox="0 0 24 24">
                <path d="M13 2 4 14h7l-1 8 9-12h-7z" />
            </symbol>
            <symbol id="i-chat" viewBox="0 0 24 24">
                <path d="M21 12a8 8 0 0 1-11.7 7L3 21l2-6.1A8 8 0 1 1 21 12z" />
            </symbol>
            <symbol id="i-user" viewBox="0 0 24 24">
                <circle cx="12" cy="8" r="4" />
                <path d="M4 21c0-4 4-6 8-6s8 2 8 6" />
            </symbol>
            <symbol id="i-smile" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <path d="M8 14s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01" />
            </symbol>
            <symbol id="i-hash" viewBox="0 0 24 24">
                <path d="M4 9h16M4 15h16M10 3 8 21M16 3l-2 18" />
            </symbol>
            <symbol id="i-drop" viewBox="0 0 24 24">
                <path d="M12 2.7s7 7 7 12a7 7 0 0 1-14 0c0-5 7-12 7-12z" />
            </symbol>
            <symbol id="i-cal" viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" />
                <path d="M16 2v4M8 2v4M3 10h18" />
            </symbol>
            <symbol id="i-target" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <circle cx="12" cy="12" r="6" />
                <circle cx="12" cy="12" r="2" />
            </symbol>
            <symbol id="i-trend" viewBox="0 0 24 24">
                <path d="m22 7-8.5 8.5-5-5L2 17" />
                <path d="M16 7h6v6" />
            </symbol>
            <symbol id="i-store" viewBox="0 0 24 24">
                <path d="M3 9l1.5-5h15L21 9" />
                <path d="M4 9v11h16V9" />
                <path d="M9 20v-6h6v6" />
            </symbol>
            <symbol id="i-check" viewBox="0 0 24 24">
                <rect x="8" y="2" width="8" height="4" rx="1" />
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2M9 14l2 2 4-4" />
            </symbol>
            <symbol id="i-type" viewBox="0 0 24 24">
                <path d="M4 7V4h16v3M9 20h6M12 4v16" />
            </symbol>
            <symbol id="i-eye" viewBox="0 0 24 24">
                <path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z" />
                <circle cx="12" cy="12" r="3" />
            </symbol>
            <symbol id="i-file" viewBox="0 0 24 24">
                <path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7z" />
                <path d="M14 2v5h5M9 13h6M9 17h6" />
            </symbol>
        </defs>
    </svg>


    <header class="site-head">
        <div class="wrap">
            <a class="brand" href="#"><span class="om" aria-hidden="true">ॐ</span>Occult Sciences</a>
            <button class="btn sm js-open" type="button">Get report</button>
        </div>
    </header>

    <div class="strip">
        <ul>
            <li><svg class="i">
                    <use href="#i-lock" />
                </svg>Secure payment</li>
            <li><svg class="i">
                    <use href="#i-bolt" />
                </svg>Instant PDF delivery</li>
            <li><svg class="i">
                    <use href="#i-chat" />
                </svg>On email + WhatsApp</li>
            <li><svg class="i">
                    <use href="#i-user" />
                </svg>Personalised report</li>
        </ul>
    </div>

    <!-- HERO -->
    <section class="hero" style="padding-bottom:clamp(48px,7vw,80px)">
        <div class="wrap grid">
            <div>
                <p class="kicker">॥ शुभ नाम · शुभ भाग्य ॥</p>
                <h1>Is your name quietly working against your growth?</h1>
                <p class="sub">Every letter in your name carries a number. Find out which ones support you, which
                    ones don't, and how to fix the gap with small, practical changes.</p>

                <div class="tiles" aria-hidden="true">
                    <div class="tile"><b>9</b><span>R</span></div>
                    <div class="tile"><b>1</b><span>A</span></div>
                    <div class="tile hl"><b>8</b><span>H</span></div>
                    <div class="tile"><b>3</b><span>U</span></div>
                    <div class="tile"><b>3</b><span>L</span></div>
                </div>
                <p class="tiles-cap">Each letter maps to a number. Your report reads the pattern.</p>

                <ul class="pills">
                    <li>Attract money</li>
                    <li>Clear obstacles</li>
                    <li>Build confidence</li>
                </ul>

                <div class="price">
                    <span class="now">₹{{ $pageinfo['price'] ?? '' }}</span>
                    <span class="was">₹{{ $pageinfo['mrp'] ?? '' }}</span>
                    @if ($pageinfo['discount'] > 0)
                        <span class="off">{{ $pageinfo['discount'] ?? '' }}% OFF</span>
                    @endif
                </div>
                <span class="limited">Limited-time offer. Save ₹1,300 today.</span>
            </div>

            <div class="card" id="heroForm" aria-label="Order form"></div>
        </div>
    </section>

    <div class="toran" aria-hidden="true"></div>

    <!-- BONUSES -->
    <section>
        <div class="wrap center">
            <h2>3+ free remedy guides worth ₹10,000</h2>
            <p class="lede">Short, easy-to-follow guides for the areas people ask about most. Included at no extra
                cost.</p>

            <div class="bonus-grid">
                <div class="bonus"><span class="ico"><svg class="i">
                            <use href="#i-rupee" />
                        </svg></span>
                    <h3>Money flow remedy</h3>
                    <p>A daily habit to steady your income and spending energy.</p>
                </div>
                <div class="bonus"><span class="ico"><svg class="i">
                            <use href="#i-trend" />
                        </svg></span>
                    <h3>Client attraction remedy</h3>
                    <p>For business owners who want more enquiries and repeat customers.</p>
                </div>
                <div class="bonus"><span class="ico"><svg class="i">
                            <use href="#i-briefcase" />
                        </svg></span>
                    <h3>Dream job switchword</h3>
                    <p>A focused phrase and routine for interviews and applications.</p>
                </div>
                <div class="bonus"><span class="ico"><svg class="i">
                            <use href="#i-grad" />
                        </svg></span>
                    <h3>Study focus switchword</h3>
                    <p>Helps students build concentration and consistency.</p>
                </div>
                <div class="bonus"><span class="ico"><svg class="i">
                            <use href="#i-smile" />
                        </svg></span>
                    <h3>Stress release switchword</h3>
                    <p>A two-minute reset for anxious or overloaded days.</p>
                </div>
                <div class="bonus"><span class="ico"><svg class="i">
                            <use href="#i-heart" />
                        </svg></span>
                    <h3>Love and relationship remedy</h3>
                    <p>Small changes to invite harmony and new connection.</p>
                </div>
                <div class="bonus"><span class="ico"><svg class="i">
                            <use href="#i-sun" />
                        </svg></span>
                    <h3>Energy and routine guide</h3>
                    <p>Align your day with your lucky numbers, colours and timings.</p>
                </div>
                <div class="bonus"><span class="ico"><svg class="i">
                            <use href="#i-shield" />
                        </svg></span>
                    <h3>Protection ritual</h3>
                    <p>A simple practice for feeling calm and grounded around negativity.</p>
                </div>
            </div>

            <span class="value">Total value ₹10,000 &nbsp;·&nbsp; <b>Yours free</b></span>
        </div>
    </section>

    <!-- WHAT'S INSIDE -->
    <section class="inside">
        <div class="wrap">
            <h2>What's inside your report</h2>
            <p class="lede">A complete reading of your name and how it shapes your money, work and relationships.</p>
            <div class="inside-grid">
                <div><span class="ico"><svg class="i">
                            <use href="#i-user" />
                        </svg></span>
                    <h3>Name meaning</h3>
                    <p>The hidden vibration and strengths your name carries.</p>
                </div>
                <div><span class="ico"><svg class="i">
                            <use href="#i-type" />
                        </svg></span>
                    <h3>Best spelling</h3>
                    <p>Spelling options matched to your birth details.</p>
                </div>
                <div><span class="ico"><svg class="i">
                            <use href="#i-star" />
                        </svg></span>
                    <h3>Famous name changes</h3>
                    <p>How well-known people adjusted their names for growth.</p>
                </div>
                <div><span class="ico"><svg class="i">
                            <use href="#i-hash" />
                        </svg></span>
                    <h3>Lucky numbers</h3>
                    <p>Numbers to use for dates, phone numbers and decisions.</p>
                </div>
                <div><span class="ico"><svg class="i">
                            <use href="#i-unlock" />
                        </svg></span>
                    <h3>Blocks to fix</h3>
                    <p>Letters and patterns that may be slowing you down.</p>
                </div>
                <div><span class="ico"><svg class="i">
                            <use href="#i-check" />
                        </svg></span>
                    <h3>Personal remedies</h3>
                    <p>Easy steps you can start the same day.</p>
                </div>
                <div><span class="ico"><svg class="i">
                            <use href="#i-drop" />
                        </svg></span>
                    <h3>Lucky colours and dates</h3>
                    <p>Plan your week and wardrobe around supportive energy.</p>
                </div>
                <div><span class="ico"><svg class="i">
                            <use href="#i-cal" />
                        </svg></span>
                    <h3>Action plan</h3>
                    <p>A step-by-step guide to apply everything.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- PROBLEMS -> SOLUTIONS -->
    <section>
        <div class="wrap">
            <h2>Where it hurts, and what the report does about it</h2>
            <p class="lede">If any of these sound familiar, your name may be part of the pattern. Each problem comes
                with a matching fix inside the report.</p>

            <div class="ps">
                <div class="ps-row">
                    <div class="p"><small>Problem</small>
                        <h3>Stuck in your career</h3>
                        <p>Hard work, but no promotion, raise or breakthrough.</p>
                    </div>
                    <div class="mid" aria-hidden="true">→</div>
                    <div class="s"><small>Solution</small>
                        <h3>Career-aligned spelling</h3>
                        <p>A spelling and number plan for growth, plus a job-switch remedy.</p>
                    </div>
                </div>
                <div class="ps-row">
                    <div class="p"><small>Problem</small>
                        <h3>Money never stays</h3>
                        <p>Income comes in but savings and stability stay out of reach.</p>
                    </div>
                    <div class="mid" aria-hidden="true">→</div>
                    <div class="s"><small>Solution</small>
                        <h3>Wealth number alignment</h3>
                        <p>Lucky numbers, dates and the money flow remedy.</p>
                    </div>
                </div>
                <div class="ps-row">
                    <div class="p"><small>Problem</small>
                        <h3>Strained relationships</h3>
                        <p>Repeated misunderstandings with partner, family or colleagues.</p>
                    </div>
                    <div class="mid" aria-hidden="true">→</div>
                    <div class="s"><small>Solution</small>
                        <h3>Harmony guidance</h3>
                        <p>Name-based insight into how you come across, plus a relationship remedy.</p>
                    </div>
                </div>
                <div class="ps-row">
                    <div class="p"><small>Problem</small>
                        <h3>Low confidence</h3>
                        <p>You hold back even when you know you're capable.</p>
                    </div>
                    <div class="mid" aria-hidden="true">→</div>
                    <div class="s"><small>Solution</small>
                        <h3>Strength activation</h3>
                        <p>Know your strongest traits and use them deliberately, with a stress-release switchword.</p>
                    </div>
                </div>
                <div class="ps-row">
                    <div class="p"><small>Problem</small>
                        <h3>Repeated obstacles</h3>
                        <p>Plans keep getting delayed or derailed for no clear reason.</p>
                    </div>
                    <div class="mid" aria-hidden="true">→</div>
                    <div class="s"><small>Solution</small>
                        <h3>Block removal plan</h3>
                        <p>Spot the letters creating friction and correct them step by step.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- OUTCOMES -->
    <section class="outcomes">
        <div class="wrap">
            <h2>What you can expect to work on</h2>
            <ul class="out-list">
                <li><span class="ico"><svg class="i">
                            <use href="#i-rupee" />
                        </svg></span><b>Attract wealth</b><span>Use the right spelling and numbers for money.</span>
                </li>
                <li><span class="ico"><svg class="i">
                            <use href="#i-heart" />
                        </svg></span><b>Stronger relationships</b><span>Better understanding at home, at work and
                        socially.</span></li>
                <li><span class="ico"><svg class="i">
                            <use href="#i-smile" />
                        </svg></span><b>More confidence</b><span>Show up with clarity and presence.</span></li>
                <li><span class="ico"><svg class="i">
                            <use href="#i-unlock" />
                        </svg></span><b>Fewer obstacles</b><span>Address the blocks in your name pattern.</span></li>
                <li><span class="ico"><svg class="i">
                            <use href="#i-eye" />
                        </svg></span><b>Better recognition</b><span>Strengthen your public image and influence.</span>
                </li>
                <li><span class="ico"><svg class="i">
                            <use href="#i-target" />
                        </svg></span><b>Clearer decisions</b><span>Choose partners, business names and ventures with
                        more insight.</span></li>
            </ul>
        </div>
    </section>

    <!-- WHO -->
    <section>
        <div class="wrap">
            <h2>Who this report is for</h2>
            <div class="who">
                <div><span class="ico"><svg class="i">
                            <use href="#i-store" />
                        </svg></span>
                    <h3>Business owners</h3>
                    <p>Looking for steady growth and better brand or business names.</p>
                </div>
                <div><span class="ico"><svg class="i">
                            <use href="#i-briefcase" />
                        </svg></span>
                    <h3>Working professionals</h3>
                    <p>Feeling stuck and ready for a change in direction.</p>
                </div>
                <div><span class="ico"><svg class="i">
                            <use href="#i-grad" />
                        </svg></span>
                    <h3>Students and job seekers</h3>
                    <p>Who want better opportunities and sharper focus.</p>
                </div>
                <div><span class="ico"><svg class="i">
                            <use href="#i-trend" />
                        </svg></span>
                    <h3>Anyone working hard</h3>
                    <p>Without seeing results that match the effort.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- OFFER -->
    <section class="offer">
        <div class="wrap">
            <div>
                <h2>Get your report today</h2>
                <p>Personalised to your name and date of birth. Delivered instantly.</p>
                <div class="price" style="margin-top:12px">
                    <span class="now">₹{{ $pageinfo['price'] ?? '' }}</span>
                    <span class="was">₹{{ $pageinfo['mrp'] ?? '' }}</span>
                    @if (($pageinfo['discount'] ?? '') > 0)
                        <span class="off">{{ $pageinfo['discount'] ?? '' }}% OFF</span>
                    @endif
                </div>
                <span class="limited">Limited-time offer</span>
            </div>
            <button class="btn js-open" type="button">Get my report</button>
        </div>
    </section>

    <!-- ABOUT -->
    <section>
        <div class="wrap about">
            <div>
                <h2>Built by a team that has done this thousands of times</h2>
                <p>Occult Sciences combines classical numerology with a practical approach. No jargon and no vague
                    promises. Each report gives you clear findings, spelling options and steps you can act on.</p>
                <p>Our reports are generated from your own name and birth details, then reviewed for clarity so you can
                    use them right away.</p>
            </div>
            <div class="stats" id="stats">
                <div class="stat" data-k="sold"><b></b><span>Reports sold</span></div>
                <div class="stat" data-k="sat"><b></b><span>Satisfaction rate</span></div>
                <div class="stat" data-k="years"><b></b><span>Of experience</span></div>
                <div class="stat"><b>PDF</b><span>On email and WhatsApp</span></div>
            </div>
        </div>
    </section>

    <!-- REVIEWS -->
    <section style="background:var(--paper)">
        <div class="wrap center">
            <h2>What customers say</h2>
            <p class="lede">Launch se pehle in cards mein apne asli customers ke reviews daalein. Verified badge sirf
                unhi par rakhein jinhe aap verify kar sakein.</p>
            <div class="carousel" id="carousel">
                <div class="track" id="track" tabindex="0" aria-label="Customer reviews">
                    <figure class="slide">
                        <div class="review">
                            <div class="stars" aria-label="5 out of 5">★★★★★</div>
                            <p>“[Yahan apne asli customer ka review daalein: report kitni clear lagi]”</p>
                            <div class="who-row"><cite>[Customer ka naam]<span>[Sheher]</span></cite><span
                                    class="vbadge"><svg viewBox="0 0 20 20" width="14" height="14"
                                        aria-hidden="true">
                                        <circle cx="10" cy="10" r="10" fill="#2f9e44" />
                                        <path d="M5.5 10.4l3 3 6-6.4" fill="none" stroke="#fff" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>Verified review</span></div>
                        </div>
                    </figure>
                    <figure class="slide">
                        <div class="review">
                            <div class="stars" aria-label="5 out of 5">★★★★★</div>
                            <p>“[Yahan asli review: remedies se kya fark mehsoos hua]”</p>
                            <div class="who-row"><cite>[Customer ka naam]<span>[Sheher]</span></cite><span
                                    class="vbadge"><svg viewBox="0 0 20 20" width="14" height="14"
                                        aria-hidden="true">
                                        <circle cx="10" cy="10" r="10" fill="#2f9e44" />
                                        <path d="M5.5 10.4l3 3 6-6.4" fill="none" stroke="#fff" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>Verified review</span></div>
                        </div>
                    </figure>
                    <figure class="slide">
                        <div class="review">
                            <div class="stars" aria-label="5 out of 5">★★★★★</div>
                            <p>“[Yahan asli review: career ya business ke baare mein feedback]”</p>
                            <div class="who-row"><cite>[Customer ka naam]<span>[Sheher]</span></cite><span
                                    class="vbadge"><svg viewBox="0 0 20 20" width="14" height="14"
                                        aria-hidden="true">
                                        <circle cx="10" cy="10" r="10" fill="#2f9e44" />
                                        <path d="M5.5 10.4l3 3 6-6.4" fill="none" stroke="#fff" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>Verified review</span></div>
                        </div>
                    </figure>
                    <figure class="slide">
                        <div class="review">
                            <div class="stars" aria-label="5 out of 5">★★★★★</div>
                            <p>“[Yahan asli review: report samajhna kitna aasaan raha]”</p>
                            <div class="who-row"><cite>[Customer ka naam]<span>[Sheher]</span></cite><span
                                    class="vbadge"><svg viewBox="0 0 20 20" width="14" height="14"
                                        aria-hidden="true">
                                        <circle cx="10" cy="10" r="10" fill="#2f9e44" />
                                        <path d="M5.5 10.4l3 3 6-6.4" fill="none" stroke="#fff" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>Verified review</span></div>
                        </div>
                    </figure>
                    <figure class="slide">
                        <div class="review">
                            <div class="stars" aria-label="5 out of 5">★★★★★</div>
                            <p>“[Yahan asli review: relationships ya confidence ke baare mein]”</p>
                            <div class="who-row"><cite>[Customer ka naam]<span>[Sheher]</span></cite><span
                                    class="vbadge"><svg viewBox="0 0 20 20" width="14" height="14"
                                        aria-hidden="true">
                                        <circle cx="10" cy="10" r="10" fill="#2f9e44" />
                                        <path d="M5.5 10.4l3 3 6-6.4" fill="none" stroke="#fff" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>Verified review</span></div>
                        </div>
                    </figure>
                </div>
                <div class="car-ctrl">
                    <button class="car-btn" id="prev" type="button" aria-label="Previous review">‹</button>
                    <div class="dots" id="dots"></div>
                    <button class="car-btn" id="next" type="button" aria-label="Next review">›</button>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section>
        <div class="wrap">
            <h2 class="center">Questions, answered</h2>
            <div class="faq">
                <details>
                    <summary>What is a name numerology report?</summary>
                    <p>A personalised analysis of how the numbers in your name relate to your money, career,
                        relationships and day-to-day energy, with suggested spellings and remedies.</p>
                </details>
                <details>
                    <summary>How is it personalised?</summary>
                    <p>It is built from your full name, date of birth and gender, so no two reports are the same.</p>
                </details>
                <details>
                    <summary>How will I receive it?</summary>
                    <p>As a PDF on your email and WhatsApp, straight after payment.</p>
                </details>
                <details>
                    <summary>How long does delivery take?</summary>
                    <p>It is instant after a successful payment.</p>
                </details>
                <details>
                    <summary>Can I get my name corrected?</summary>
                    <p>The report includes recommended spellings. If you want one-to-one help choosing, you can book a
                        consultation after receiving it.</p>
                </details>
                <details>
                    <summary>Does numerology guarantee results?</summary>
                    <p>No. Numerology is a belief-based guidance tool, not a scientific or medical one. Treat the report
                        as guidance and combine it with your own effort and professional advice where needed.</p>
                </details>
                {{-- <details>
                    <summary>What is the refund policy?</summary>
                    <p>Please read our <a href="/refund-policy">Refund Policy</a> for details.</p>
                </details> --}}
            </div>
        </div>
    </section>

    <!-- FINAL CTA -->
    <section class="final center">
        <div class="wrap">
            <h2>See what your name is really saying</h2>
            <p class="lede">Unlock your report at the special price and start with the first remedy today.</p>
            <div class="price"><span class="now">₹{{ $pageinfo['price'] ?? '' }}</span><span
                    class="was">₹{{ $pageinfo['mrp'] ?? '' }}</span>
                @if (($pageinfo['discount'] ?? 0) > 0)
                    <span class="off">{{ $pageinfo['discount'] ?? '' }}% OFF</span>
                @endif
            </div>
            <span class="limited">Limited-time offer</span><br>
            <button class="btn js-open" type="button">Get my report</button>
        </div>
    </section>

    <footer>
        <div class="wrap">
            <p>© 2026 Occult Sciences. All rights reserved.</p>
            {{-- <p><a href="/privacy-policy">Privacy</a><a href="/terms">Terms</a><a href="/refund-policy">Refunds</a> --}}
            </p>
            {{-- <p>Numerology reports are for guidance and entertainment. They are not a substitute for medical, legal or
                financial advice.</p> --}}
        </div>
    </footer>

    <!-- STICKY BAR -->
    <div class="sticky" id="sticky">
        <div class="wrap">
            <div class="p"><b>₹{{ $pageinfo['price'] ?? '' }}</b><s>₹{{ $pageinfo['mrp'] ?? '' }}</s>
                @if (($pageinfo['discount'] ?? 0) > 0)
                    <em>{{ $pageinfo['discount'] ?? '' }}% OFF</em>
                @endif
            </div>
            <button class="btn js-open" type="button">Get my report</button>
        </div>
    </div>

    <!-- MODAL -->
    <div class="modal" id="modal" role="dialog" aria-modal="true" aria-label="Get your personalised report">
        <div class="card" id="modalForm">
            <button class="x" type="button" id="closeModal" aria-label="Close">×</button>
        </div>
    </div>

    <!-- FORM TEMPLATE -->
    <template id="formTpl">
        <h2>Get your name numerology report</h2>
        <div class="sum">
            <div><b>Name Numerology Report</b><span>Personalised PDF + 3+ free remedy guides</span></div>
            <div class="sp"><s>₹{{ $pageinfo['mrp'] ?? '' }}</s><strong>₹{{ $pageinfo['price'] ?? '' }}</strong>
                @if (($pageinfo['discount'] ?? 0) > 0)
                    <em>{{ $pageinfo['discount'] ?? '' }}% OFF</em>
                @endif
            </div>
        </div>
        <p class="mini">Apni details bharein. Report inhi se banegi.</p>

        <form class="orderForm" method="POST" action="/checkout" novalidate>
            @csrf
            <input type="hidden" name="amount" value="{{ $pageinfo['price'] ?? 0 }}">
            <div class="field">
                <label>Full name</label>
                <input name="name" type="text" autocomplete="name" placeholder="As written on your documents"
                    required>
                <div class="err">Enter your full name.</div>
            </div>

            <div class="field">
                <label>Email address</label>
                <input name="email" type="email" autocomplete="email" placeholder="you@example.com" required>
                <div class="err">Enter a valid email address.</div>
            </div>

            <div class="field">
                <label>Mobile number</label>
                <input name="mobile" type="tel" inputmode="numeric" autocomplete="tel-national"
                    placeholder="10-digit WhatsApp number" maxlength="10" required>
                <div class="err">Enter a valid 10-digit mobile number.</div>
            </div>

            <div class="two">
                <div class="field">
                    <label>Date of birth</label>
                    <input name="dob" type="date" required>
                    <div class="err">Select a valid date of birth.</div>
                </div>

                <div class="field">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="">Select</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                    <div class="err">Select your gender.</div>
                </div>
            </div>

            <div class="total">
                <span>Total</span>
                <span class="r">
                    <s class="mrp">₹{{ $pageinfo['mrp'] ?? '' }}</s>
                    <strong class="amt">₹{{ $pageinfo['price'] ?? '' }}</strong>

                    @if (($pageinfo['discount'] ?? 0) > 0)
                        <span class="off">
                            {{ $pageinfo['discount'] }}% OFF
                        </span>
                    @endif
                </span>
            </div>

            <button class="btn block submitBtn" type="submit">
                <span class="btnText">Proceed to Payment</span>
                <span class="btnLoader" aria-hidden="true"></span>
            </button>

            <div class="pay-note" role="status"></div>

            <div class="safe">
                <span>🔒 100% secure payment</span>
                <span>⚡ Instant delivery</span>
            </div>
        </form>

    </template>


    <!-- PayU Bolt checkout (UAT / Production URL: config/services.php -> payu.bolt_script) -->
    <script src="{{ config('services.payu.bolt_script') }}"></script>

    <script>
        (function() {
            // ---- Social proof: type ONLY real, provable figures here. Empty = hidden.
            var PROOF = {
                sold: "",
                sat: "",
                years: ""
            }; // e.g. sold: "10,000+", sat: "96%", years: "5+ years"
            (function() {
                var any = false;
                document.querySelectorAll('#stats .stat[data-k]').forEach(function(el) {
                    var v = PROOF[el.dataset.k];
                    if (v) {
                        el.querySelector('b').textContent = v;
                        any = true;
                    } else {
                        el.style.display = 'none';
                    }
                });
                if (!any) {
                    var st = document.getElementById('stats');
                    st.style.display = 'none';
                    st.closest('.about').classList.add('solo');
                }
            })();

            var CHECKOUT_URL = @json(route('checkout'));
            var CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var tpl = document.getElementById('formTpl');
            var hero = document.getElementById('heroForm');
            var modalCard = document.getElementById('modalForm');
            var modal = document.getElementById('modal');
            var sticky = document.getElementById('sticky');

            hero.appendChild(tpl.content.cloneNode(true));
            modalCard.appendChild(tpl.content.cloneNode(true));

            // ---- PayU checkout popup
            function field(form, name) {
                return form.querySelector('[name="' + name + '"]');
            }

            function validate(form) {
                var ok = true;

                function mark(el, valid) {
                    el.closest('.field').classList.toggle('bad', !valid);
                    if (!valid) ok = false;
                }
                mark(field(form, 'name'), field(form, 'name').value.trim().length > 1);
                mark(field(form, 'email'), /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(field(form, 'email').value.trim()));
                mark(field(form, 'mobile'), /^[6-9]\d{9}$/.test(field(form, 'mobile').value.trim()));
                mark(field(form, 'dob'), !!field(form, 'dob').value);
                mark(field(form, 'gender'), !!field(form, 'gender').value);
                return ok;
            }

            function setBusy(form, busy) {
                form.querySelector('.submitBtn').disabled = busy;
                form.querySelector('.btnText').textContent = busy ? 'Processing…' : 'Proceed to Payment';
            }

            function showNote(form, msg) {
                var note = form.querySelector('.pay-note');
                note.textContent = msg || '';
                note.style.display = msg ? 'block' : 'none';
            }

            // PayU ka response server (surl / furl) par POST karta hai, wahan hash verify hoga
            function postTo(url, fields) {
                var data = Object.assign({}, fields, {
                    _token: CSRF
                });
                var f = document.createElement('form');
                f.method = 'POST';
                f.action = url;
                f.style.display = 'none';
                Object.keys(data).forEach(function(k) {
                    var v = data[k];
                    if (v === null || v === undefined) return;
                    var i = document.createElement('input');
                    i.type = 'hidden';
                    i.name = k;
                    i.value = typeof v === 'object' ? JSON.stringify(v) : v;
                    f.appendChild(i);
                });
                document.body.appendChild(f);
                f.submit();
            }

            function launchPayU(form, payu) {
                var inModal = !!form.closest('.modal');
                if (inModal) closeModal();

                bolt.launch(payu, {
                    responseHandler: function(BOLT) {
                        var r = BOLT.response || {};
                        if (r.txnStatus === 'CANCEL') {
                            setBusy(form, false);
                            if (inModal) openModal();
                            showNote(form, 'Payment was cancelled. You can try again.');
                            return;
                        }
                        postTo(r.txnStatus === 'SUCCESS' ? payu.surl : payu.furl, r);
                    },
                    catchException: function(BOLT) {
                        setBusy(form, false);
                        if (inModal) openModal();
                        showNote(form, (BOLT && BOLT.message) || 'Payment could not start. Please try again.');
                    }
                });
            }

            document.querySelectorAll('.orderForm').forEach(function(form) {
                field(form, 'mobile').addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '');
                });

                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    showNote(form, '');
                    if (!validate(form)) return;

                    if (typeof bolt === 'undefined') {
                        showNote(form, 'Payment window could not load. Please refresh the page and try again.');
                        return;
                    }

                    setBusy(form, true);

                    fetch(CHECKOUT_URL, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': CSRF,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new FormData(form),
                            credentials: 'same-origin'
                        })
                        .then(function(res) {
                            return res.json().catch(function() {
                                return {};
                            }).then(function(json) {
                                if (res.ok && json.payment) return json.payment;
                                var msg = json.message || 'Something went wrong. Please try again.';
                                if (json.errors) msg = json.errors[Object.keys(json.errors)[0]][0];
                                if (res.status === 419) msg = 'Session expired. Please refresh the page and try again.';
                                throw new Error(msg);
                            });
                        })
                        .then(function(payu) {
                            launchPayU(form, payu);
                        })
                        .catch(function(err) {
                          console.log(err);
                          
                            setBusy(form, false);
                            showNote(form, err.message || 'Something went wrong. Please try again.');
                        });
                });
            });

            var lastBtn = null;

            function openModal() {
                modal.classList.add('open');
                document.body.style.overflow = 'hidden';
                var f = modal.querySelector('input[name=name]');
                if (f) setTimeout(function() {
                    f.focus({
                        preventScroll: true
                    });
                }, 50);
            }

            function closeModal() {
                modal.classList.remove('open');
                document.body.style.overflow = '';
                if (lastBtn) lastBtn.focus();
            }
            document.querySelectorAll('.js-open').forEach(function(b) {
                b.addEventListener('click', function() {
                    lastBtn = b;
                    openModal();
                });
            });
            document.getElementById('closeModal').addEventListener('click', closeModal);
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeModal();
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeModal();
            });

            // ---- review carousel
            var track = document.getElementById('track'),
                dots = document.getElementById('dots');
            var slidesEl = track.querySelectorAll('.slide');

            function perView() {
                return Math.max(1, Math.round(track.clientWidth / slidesEl[0].getBoundingClientRect().width));
            }

            function step() {
                return slidesEl[0].getBoundingClientRect().width + 16;
            }

            function buildDots() {
                dots.innerHTML = '';
                var pages = Math.max(1, slidesEl.length - perView() + 1);
                for (var i = 0; i < pages; i++) {
                    (function(i) {
                        var d = document.createElement('button');
                        d.type = 'button';
                        d.setAttribute('aria-label', 'Go to review ' + (i + 1));
                        d.addEventListener('click', function() {
                            track.scrollTo({
                                left: i * step()
                            });
                        });
                        dots.appendChild(d);
                    })(i);
                }
                mark();
            }

            function mark() {
                var i = Math.round(track.scrollLeft / step());
                dots.querySelectorAll('button').forEach(function(d, k) {
                    d.classList.toggle('on', k === i);
                });
            }
            document.getElementById('prev').addEventListener('click', function() {
                track.scrollBy({
                    left: -step()
                });
            });
            document.getElementById('next').addEventListener('click', function() {
                track.scrollBy({
                    left: step()
                });
            });
            track.addEventListener('scroll', function() {
                window.requestAnimationFrame(mark);
            }, {
                passive: true
            });
            window.addEventListener('resize', buildDots);
            buildDots();

            var heroEl = document.querySelector('.hero');

            function onScroll() {
                sticky.classList.toggle('show', window.scrollY > heroEl.offsetHeight - 120);
            }
            window.addEventListener('scroll', onScroll, {
                passive: true
            });
            onScroll();
        })();
    </script>

</body>

</html>