<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodFusion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ── Global reset ─────────────────────────────────── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #111;
        }

        /* ════════════════════════════════════════════════════
       MODERN NAVBAR
    ════════════════════════════════════════════════════ */
        .nav-bar {
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
            background: rgba(10, 10, 10, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }

        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        /* Logo */
        .nav-logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.55rem;
            letter-spacing: 0.08em;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .nav-logo-dot {
            display: block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e8a040, #c94f20);
            flex-shrink: 0;
        }

        /* Centre links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
            flex: 1;
            justify-content: center;
            flex-wrap: wrap;
        }

        .nav-links a {
            font-size: 0.82rem;
            font-weight: 500;
            letter-spacing: 0.04em;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 6px;
            transition: color 0.2s, background 0.2s;
            white-space: nowrap;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
        }

        /* Dropdown */
        .nav-dropdown {
            position: relative;
        }

        .nav-dropdown-toggle {
            font-size: 0.82rem;
            font-weight: 500;
            letter-spacing: 0.04em;
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            padding: 6px 14px;
            border-radius: 6px;
            transition: color 0.2s, background 0.2s;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            white-space: nowrap;
        }

        .nav-dropdown-toggle::after {
            content: '▾';
            font-size: 0.7em;
            opacity: 0.6;
        }

        .nav-dropdown:hover .nav-dropdown-toggle {
            color: #fff;
            background: rgba(255, 255, 255, 0.08);
        }

        .nav-dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            background: rgba(18, 18, 18, 0.96);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 8px;
            min-width: 200px;
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.5);
            z-index: 100;
        }

        .nav-dropdown:hover .nav-dropdown-menu {
            display: block;
        }

        .nav-dropdown-menu a {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            padding: 8px 14px;
            border-radius: 6px;
            transition: background 0.2s, color 0.2s;
            white-space: nowrap;
        }

        .nav-dropdown-menu a:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        /* Right side: auth area */
        .nav-auth {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .nav-welcome {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.55);
            font-weight: 400;
        }

        .nav-welcome strong {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
        }

        .nav-btn {
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.04em;
            padding: 8px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .nav-btn-ghost {
            background: transparent;
            color: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .nav-btn-ghost:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #fff;
        }

        .nav-btn-primary {
            background: linear-gradient(135deg, #e8a040, #c94f20);
            color: #fff;
            border: none;
            box-shadow: 0 4px 14px rgba(200, 80, 30, 0.35);
        }

        .nav-btn-primary:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(200, 80, 30, 0.45);
        }

        .nav-logout {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.45);
            text-decoration: none;
            padding: 4px 10px;
            border-radius: 6px;
            transition: color 0.2s, background 0.2s;
        }

        .nav-logout:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.07);
        }

        /* Popups */
        .nav-popup {
            display: none;
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            background: rgba(18, 18, 18, 0.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 32px;
            width: 340px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.6);
            color: #fff;
        }

        .nav-popup h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.6rem;
            letter-spacing: 0.06em;
            margin-bottom: 20px;
            color: #fff;
        }

        .nav-popup input {
            width: 100%;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 8px;
            padding: 10px 14px;
            color: #fff;
            font-size: 0.88rem;
            margin-bottom: 12px;
            outline: none;
            transition: border-color 0.2s;
        }

        .nav-popup input::placeholder {
            color: rgba(255, 255, 255, 0.35);
        }

        .nav-popup input:focus {
            border-color: #e8a040;
        }

        .nav-popup .popup-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #e8a040, #c94f20);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.88rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 4px;
            letter-spacing: 0.04em;
            transition: filter 0.2s;
        }

        .nav-popup .popup-submit:hover {
            filter: brightness(1.1);
        }

        .nav-popup .popup-close {
            display: block;
            text-align: center;
            margin-top: 14px;
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.4);
            cursor: pointer;
            background: none;
            border: none;
            letter-spacing: 0.04em;
            transition: color 0.2s;
            width: 100%;
        }

        .nav-popup .popup-close:hover {
            color: rgba(255, 255, 255, 0.8);
        }

        /* ════════════════════════════════════════════════════
       CINEMATIC HERO SLIDER — FIXED CENTRE APPROACH
    ════════════════════════════════════════════════════ */

        .fh-hero {
            position: relative;
            width: 100%;
            height: calc(100vh - 64px);
            min-height: 540px;
            overflow: hidden;
            user-select: none;
            touch-action: pan-y;
        }

        /* Smooth colour background */
        .fh-bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            transition: background-color 0.7s cubic-bezier(.4, 0, .2, 1);
        }

        /* Noise grain overlay */
        .fh-grain {
            position: absolute;
            inset: 0;
            z-index: 1;
            pointer-events: none;
            opacity: 0.4;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.08'/%3E%3C/svg%3E");
            mix-blend-mode: overlay;
        }

        /* Vignette */
        .fh-vignette {
            position: absolute;
            inset: 0;
            z-index: 2;
            background: radial-gradient(ellipse at center, transparent 40%, rgba(0, 0, 0, 0.32) 100%);
            pointer-events: none;
        }

        /* ── Top meta row ─────────────────── */
        .fh-meta {
            position: absolute;
            top: 32px;
            left: 0;
            right: 0;
            z-index: 20;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0 52px;
            pointer-events: none;
            color: var(--fh-text, #f0ece4);
        }

        .fh-meta-col {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .fh-meta-col.right {
            text-align: right;
        }

        .fh-meta-label {
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            opacity: 0.45;
        }

        .fh-meta-val {
            font-size: 0.88rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            opacity: 0.85;
        }

        /* Brand centre */
        .fh-brand {
            position: absolute;
            top: 32px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 20;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 0.72rem;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: var(--fh-text, #f0ece4);
            opacity: 0.5;
            white-space: nowrap;
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .fh-brand::before,
        .fh-brand::after {
            content: '';
            display: block;
            width: 32px;
            height: 1px;
            background: currentColor;
        }

        /* ── Giant BG headline ────────────── */
        .fh-headline {
            position: absolute;
            inset: 0;
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Bebas Neue', 'Arial Black', sans-serif;
            font-size: clamp(72px, 13vw, 170px);
            line-height: 0.86;
            letter-spacing: 0.04em;
            text-align: center;
            color: var(--fh-text, #f0ece4);
            opacity: 0.07;
            pointer-events: none;
            white-space: pre-line;
            transition: color 0.6s ease, opacity 0.5s ease;
        }

        /* ── Stage: slides sit here, all stacked centre ── */
        .fh-stage {
            position: absolute;
            inset: 0;
            z-index: 5;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: visible;
        }

        /*
      Each slide is absolutely positioned at the same centre point.
      We use CSS custom properties --tx (translateX) and CSS classes
      to push them left/right of centre. No JS track movement needed.
    */
        .fh-slide {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            transition:
                transform 0.75s cubic-bezier(.77, 0, .18, 1),
                opacity 0.75s ease,
                filter 0.75s ease,
                z-index 0s 0.375s;
            pointer-events: none;
            will-change: transform, opacity;
        }

        /* Active = dead centre, full size, fully visible */
        .fh-slide.is-active {
            transform: translateX(0) scale(1);
            opacity: 1;
            filter: none;
            z-index: 4;
            pointer-events: auto;
            transition:
                transform 0.75s cubic-bezier(.77, 0, .18, 1),
                opacity 0.75s ease,
                filter 0.75s ease;
        }

        /* Immediate neighbours: partially visible left/right */
        .fh-slide.is-prev {
            transform: translateX(-52%) scale(0.68);
            opacity: 0.5;
            filter: brightness(0.65) saturate(0.4);
            z-index: 3;
        }

        .fh-slide.is-next {
            transform: translateX(52%) scale(0.68);
            opacity: 0.5;
            filter: brightness(0.65) saturate(0.4);
            z-index: 3;
        }

        /* Far slides: hidden off-screen */
        .fh-slide.is-far-prev {
            transform: translateX(-100%) scale(0.5);
            opacity: 0;
            filter: brightness(0.4) saturate(0.2);
            z-index: 2;
        }

        .fh-slide.is-far-next {
            transform: translateX(100%) scale(0.5);
            opacity: 0;
            filter: brightness(0.4) saturate(0.2);
            z-index: 2;
        }

        /* ── Image inside slide ──────────── */
        .fh-img {
            display: block;
            width: 420px;
            height: 420px;
            object-fit: contain;
            filter: drop-shadow(0 32px 60px rgba(0, 0, 0, 0.42));
            transition: transform 0.5s ease, filter 0.5s ease;
            pointer-events: none;
            draggable: false;
        }

        .fh-slide.is-active .fh-img {
            filter: drop-shadow(0 40px 80px rgba(0, 0, 0, 0.5));
        }

        .fh-slide.is-active:hover .fh-img {
            transform: translateY(-10px) scale(1.04);
        }

        /* ── Bottom panel ────────────────── */
        .fh-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 20;
            padding: 0 48px 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            color: var(--fh-text, #f0ece4);
            pointer-events: none;
        }

        /* Progress line + dots */
        .fh-progress-row {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 18px;
            width: 240px;
        }

        .fh-line {
            flex: 1;
            height: 1px;
            background: var(--fh-text, #f0ece4);
            opacity: 0.2;
        }

        .fh-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--fh-text, #f0ece4);
            opacity: 0.25;
            margin: 0 5px;
            cursor: pointer;
            pointer-events: auto;
            border: none;
            padding: 0;
            flex-shrink: 0;
            transition: opacity 0.3s, transform 0.3s, width 0.4s, border-radius 0.4s;
        }

        .fh-dot.active {
            opacity: 0.9;
            width: 24px;
            border-radius: 4px;
            transform: none;
        }

        .fh-desc {
            font-size: 0.8rem;
            font-weight: 300;
            line-height: 1.8;
            letter-spacing: 0.04em;
            opacity: 0.6;
            max-width: 480px;
            margin-bottom: 20px;
            transition: opacity 0.4s;
        }

        .fh-cta-btn {
            pointer-events: auto;
            display: inline-flex;
            align-items: center;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--fh-text, #f0ece4);
            border: 1.5px solid var(--fh-text, #f0ece4);
            border-radius: 50px;
            padding: 11px 34px;
            text-decoration: none;
            opacity: 0.8;
            transition: background 0.3s, color 0.3s, opacity 0.3s, transform 0.2s;
        }

        .fh-cta-btn:hover {
            background: var(--fh-text, #f0ece4);
            color: var(--fh-bg, #333);
            opacity: 1;
            transform: scale(1.04);
        }

        /* ── Arrow buttons ───────────────── */
        .fh-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 25;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            border: 1.5px solid rgba(255, 255, 255, 0.18);
            color: var(--fh-text, #f0ece4);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.25s, transform 0.25s, border-color 0.25s;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .fh-arrow:hover {
            background: rgba(255, 255, 255, 0.18);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-50%) scale(1.08);
        }

        .fh-arrow:active {
            transform: translateY(-50%) scale(0.96);
        }

        .fh-arrow-left {
            left: 32px;
        }

        .fh-arrow-right {
            right: 32px;
        }

        .fh-arrow svg {
            display: block;
        }

        /* ── Text fade-in animation ──────── */
        .fh-anim-in {
            animation: fhFadeUp 0.55s cubic-bezier(.4, 0, .2, 1) both;
        }

        @keyframes fhFadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Reduced motion ──────────────── */
        @media (prefers-reduced-motion: reduce) {

            .fh-slide,
            .fh-bg,
            .fh-img,
            .fh-cta-btn,
            .fh-dot,
            .fh-arrow {
                transition: none !important;
                animation: none !important;
            }
        }

        /* ════════════════════════════════════════════════════
       RESPONSIVE
    ════════════════════════════════════════════════════ */
        @media (max-width: 1024px) {
            .fh-img {
                width: 340px;
                height: 340px;
            }

            .fh-slide.is-prev {
                transform: translateX(-48%) scale(0.64);
            }

            .fh-slide.is-next {
                transform: translateX(48%) scale(0.64);
            }
        }

        @media (max-width: 768px) {
            .fh-hero {
                height: calc(100vh - 64px);
                min-height: 500px;
            }

            .fh-img {
                width: 260px;
                height: 260px;
            }

            .fh-slide.is-prev {
                transform: translateX(-50%) scale(0.6);
            }

            .fh-slide.is-next {
                transform: translateX(50%) scale(0.6);
            }

            .fh-meta {
                padding: 0 20px;
                top: 20px;
            }

            .fh-meta-col.right {
                display: none;
            }

            .fh-brand {
                display: none;
            }

            .fh-bottom {
                padding: 0 20px 20px;
            }

            .fh-desc {
                font-size: 0.74rem;
                max-width: 90vw;
            }

            .fh-headline {
                font-size: clamp(50px, 16vw, 80px);
            }
        }

        @media (max-width: 480px) {
            .fh-img {
                width: 210px;
                height: 210px;
            }

            .fh-slide.is-prev {
                transform: translateX(-48%) scale(0.5);
                opacity: 0.4;
            }

            .fh-slide.is-next {
                transform: translateX(48%) scale(0.5);
                opacity: 0.4;
            }

            .fh-arrow {
                width: 42px;
                height: 42px;
            }

            .fh-arrow-left {
                left: 10px;
            }

            .fh-arrow-right {
                right: 10px;
            }

            .fh-cta-btn {
                font-size: 0.65rem;
                padding: 10px 24px;
            }

            .nav-inner {
                padding: 0 16px;
            }

            .nav-links {
                display: none;
            }
        }
        span .food-log{
            size: 20px;
            width: 100px;
            display: block;
        }
    </style>
</head>

<body>

    <!-- ════════════════════════════════════════════════════
     NAVBAR
════════════════════════════════════════════════════ -->
    <header class="nav-bar">
        <div class="nav-inner">

            <!-- Logo -->
            <a href="index.php" class="nav-logo">
                <span class="nav-logo-dot"></span>
                FoodFusion
                
            </a>

            <!-- Navigation links -->
            <ul class="nav-links">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="#">About Us</a></li>

                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li><a href="recipes.php">Recipe Collection</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'member'): ?>
                    <li><a href="recipes.php">Recipe Collection</a></li>
                    <li><a href="community_cookbook.php">Community Cookbook</a></li>
                    <li><a href="culinary_resources.php">Culinary Resources</a></li>
                    <li><a href="educational_resources.php">Educational Resources</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <li><a href="community_cookbook.php">Community Cookbook</a></li>
                    <li class="nav-dropdown">
                        <span class="nav-dropdown-toggle">Manage Resources</span>
                        <div class="nav-dropdown-menu">
                            <a href="admin_recipes.php">Recipes Collection</a>
                            <a href="admin_resources.php">Resources</a>
                        </div>
                    </li>
                <?php endif; ?>

                <li><a href="#">Contact Us</a></li>
            </ul>

            <!-- Auth area -->
            <div class="nav-auth">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="nav-welcome">Hello, <strong><?php echo htmlspecialchars($_SESSION['user']); ?></strong></span>
                    <a href="logout.php" class="nav-logout">Logout</a>
                <?php else: ?>
                    <button class="nav-btn nav-btn-ghost" onclick="showLogin()">Login</button>
                    <button class="nav-btn nav-btn-primary" onclick="showRegister()">Join Us</button>
                <?php endif; ?>
            </div>

        </div>
    </header>

    <!-- ════════════════════════════════════════════════════
     CINEMATIC HERO SLIDER
════════════════════════════════════════════════════ -->
    <section class="fh-hero" id="fhHero" aria-label="Food Fusion Featured Dishes">

        <!-- Colour background -->
        <div class="fh-bg" id="fhBg"></div>
        <div class="fh-grain" aria-hidden="true"></div>
        <div class="fh-vignette" aria-hidden="true"></div>

        <!-- Top brand -->
        <div class="fh-brand" id="fhBrand">Food Fusion</div>

        <!-- Top meta -->
        <div class="fh-meta">
            <div class="fh-meta-col">
                <span class="fh-meta-label">Origin</span>
                <span class="fh-meta-val" id="fhOrigin">Thailand · Southeast Asia</span>
            </div>
            <div class="fh-meta-col right">
                <span class="fh-meta-label">Style</span>
                <span class="fh-meta-val" id="fhStyle">Coconut Herb Fusion</span>
            </div>
        </div>

        <!-- Giant BG text -->
        <div class="fh-headline" id="fhHeadline" aria-hidden="true">THAI&#10;CURRY</div>

        <!-- Stage: all 5 slides stacked at centre -->
        <div class="fh-stage" id="fhStage">

            <div class="fh-slide is-active" data-idx="0">
                <img class="fh-img" src="images/food5-curry.webp"
                    alt="Thai green curry" loading="eager" draggable="false">
            </div>

            <div class="fh-slide is-next" data-idx="1">
                <img class="fh-img" src="images/food4-pasta.webp"
                    alt="Fusion linguine" loading="lazy" draggable="false">
            </div>

            <div class="fh-slide is-far-next" data-idx="2">
                <img class="fh-img" src="images/food3-pho.webp"
                    alt="Signature pho" loading="lazy" draggable="false">
            </div>

            <div class="fh-slide is-far-next" data-idx="3">
                <img class="fh-img" src="images/food2-pokebowl.png"
                    alt="Salmon poke bowl" loading="lazy" draggable="false">
            </div>

            <div class="fh-slide is-far-next" data-idx="4">
                <img class="fh-img" src="images/food1-steak.webp"
                    alt="Grilled steak" loading="lazy" draggable="false">
            </div>

        </div>

        <!-- Left / Right arrows -->
        <button class="fh-arrow fh-arrow-left" id="fhPrev" aria-label="Previous dish">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6" />
            </svg>
        </button>
        <button class="fh-arrow fh-arrow-right" id="fhNext" aria-label="Next dish">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="9 18 15 12 9 6" />
            </svg>
        </button>

        <!-- Bottom: progress + description + CTA -->
        <div class="fh-bottom">
            <div class="fh-progress-row" role="tablist" aria-label="Slide navigation">
                <div class="fh-line"></div>
                <button class="fh-dot active" role="tab" aria-selected="true" aria-label="Slide 1"></button>
                <button class="fh-dot" role="tab" aria-selected="false" aria-label="Slide 2"></button>
                <button class="fh-dot" role="tab" aria-selected="false" aria-label="Slide 3"></button>
                <button class="fh-dot" role="tab" aria-selected="false" aria-label="Slide 4"></button>
                <button class="fh-dot" role="tab" aria-selected="false" aria-label="Slide 5"></button>
                <div class="fh-line"></div>
            </div>
            <p class="fh-desc" id="fhDesc">
                A vibrant Thai green curry simmered in rich coconut milk, laced with fresh basil,
                galangal and bird's eye chilli — where ancient spice meets the modern plate.
            </p>
            <a href="recipes.php" class="fh-cta-btn">Explore Our Menu</a>
        </div>

    </section>

    <!-- ════════════════════════════════════════════════════
     AUTH POPUPS
════════════════════════════════════════════════════ -->
    <div id="registerForm" class="nav-popup">
        <h2>Join Us</h2>
        <form action="register.php" method="POST">
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="popup-submit">Create Account</button>
        </form>
        <button class="popup-close" onclick="closeAll()">✕ Close</button>
    </div>

    <div id="loginForm" class="nav-popup">
        <h2>Welcome Back</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="popup-submit">Login</button>
        </form>
        <button class="popup-close" onclick="closeAll()">✕ Close</button>
    </div>

    <script>
        /* ── Popup helpers ───────────────────────────────── */
        function showRegister() {
            document.getElementById('registerForm').style.display = 'block';
            document.getElementById('loginForm').style.display = 'none';
        }

        function showLogin() {
            document.getElementById('loginForm').style.display = 'block';
            document.getElementById('registerForm').style.display = 'none';
        }

        function closeAll() {
            document.getElementById('registerForm').style.display = 'none';
            document.getElementById('loginForm').style.display = 'none';
        }
        /* Close popup on outside click */
        document.addEventListener('click', function(e) {
            var rF = document.getElementById('registerForm');
            var lF = document.getElementById('loginForm');
            if (rF.style.display === 'block' && !rF.contains(e.target) && e.target.id !== 'registerForm') {
                var btns = document.querySelectorAll('.nav-btn');
                var hit = false;
                btns.forEach(function(b) {
                    if (b.contains(e.target)) hit = true;
                });
                if (!hit) closeAll();
            }
            if (lF.style.display === 'block' && !lF.contains(e.target)) {
                var btns2 = document.querySelectorAll('.nav-btn');
                var hit2 = false;
                btns2.forEach(function(b) {
                    if (b.contains(e.target)) hit2 = true;
                });
                if (!hit2) closeAll();
            }
        });

        /* ════════════════════════════════════════════════════
           CINEMATIC SLIDER — NO AUTOPLAY
        ════════════════════════════════════════════════════ */
        (function() {

            /* ── Per-slide data ───────────────────────────── */
            var DATA = [{
                    bg: '#3e5230',
                    /* olive green */
                    text: '#edf4e2',
                    headline: 'THAI\nCURRY',
                    origin: 'Thailand · Southeast Asia',
                    style: 'Coconut Herb Fusion',
                    desc: 'A vibrant Thai green curry simmered in rich coconut milk, laced with fresh basil, galangal and bird\'s eye chilli where ancient spice meets the modern plate.'
                },
                {
                    // bg: '#3a2314',
                    bg: '#E47E2C',
                    /* espresso brown */
                    text: '#f7eddc',
                    headline: 'FUSION\nLINGUINE',
                    origin: 'Italy · Asia Crossover',
                    style: 'Euro-Asian Pasta',
                    desc: 'Silky linguine tossed with wok-kissed chicken, cherry tomatoes and a lemongrass-cream sauce Italian tradition reimagined with the soul of the East.'
                },
                {
                    bg: '#1b3240',
                    /* deep ocean teal */
                    text: '#e2f0ee',
                    headline: 'SIGNATURE\nPHO',
                    origin: 'Vietnam · East Asia',
                    style: 'Heritage Broth Bowl',
                    desc: 'A slow-simmered bone broth crowned with rice noodles, tender beef, succulent prawns, fresh herbs and a wedge of lime harmony in every spoonful.'
                },
                {
                    // bg: '#192c38',
                    bg: '#961F0E',
                    /* dark navy */
                    text: '#dff0ec',
                    headline: 'SALMON\nPOKÉ',
                    origin: 'Hawaii · Japan Fusion',
                    style: 'Pacific Rim Bowl',
                    desc: 'Sashimi-grade salmon over sushi rice with pickled red onion, cucumber, mango and sesame a Pacific bowl bridging Honolulu and Tokyo.'
                },
                {
                    bg: '#29190a',
                    /* dark charcoal amber */
                    text: '#f4e8d2',
                    headline: 'GRILLED\nSTEAK',
                    origin: 'Argentina · France',
                    style: 'Open-Fire Classic',
                    desc: 'Prime-cut beef chargrilled over open flame, served with crisp golden fries and a smoky chimichurri dipping sauce pure indulgence, perfectly executed.'
                }
            ];

            var TOTAL = DATA.length;
            var current = 0;
            var busy = false;

            /* DOM refs */
            var hero = document.getElementById('fhHero');
            var bgEl = document.getElementById('fhBg');
            var hlEl = document.getElementById('fhHeadline');
            var originEl = document.getElementById('fhOrigin');
            var styleEl = document.getElementById('fhStyle');
            var descEl = document.getElementById('fhDesc');
            var slides = document.querySelectorAll('#fhStage .fh-slide');
            var dots = document.querySelectorAll('.fh-progress-row .fh-dot');
            var prevBtn = document.getElementById('fhPrev');
            var nextBtn = document.getElementById('fhNext');

            /* Class name sets */
            var ALL_CLS = ['is-active', 'is-prev', 'is-next', 'is-far-prev', 'is-far-next'];

            /* ── Assign position class to every slide ──────── */
            function layout(idx) {
                slides.forEach(function(sl, i) {
                    /* Remove all state classes */
                    ALL_CLS.forEach(function(c) {
                        sl.classList.remove(c);
                    });

                    var diff = i - idx;
                    /* Wrap around */
                    if (diff > Math.floor(TOTAL / 2)) diff -= TOTAL;
                    if (diff < -Math.floor(TOTAL / 2)) diff += TOTAL;

                    if (diff === 0) sl.classList.add('is-active');
                    else if (diff === -1) sl.classList.add('is-prev');
                    else if (diff === 1) sl.classList.add('is-next');
                    else if (diff < 0) sl.classList.add('is-far-prev');
                    else sl.classList.add('is-far-next');
                });
            }

            /* ── Update dots ───────────────────────────────── */
            function updateDots(idx) {
                dots.forEach(function(d, i) {
                    d.classList.toggle('active', i === idx);
                    d.setAttribute('aria-selected', i === idx ? 'true' : 'false');
                });
            }

            /* ── Apply theme colours + text ────────────────── */
            function applyTheme(idx, animate) {
                var d = DATA[idx];
                bgEl.style.backgroundColor = d.bg;
                hero.style.setProperty('--fh-text', d.text);
                hero.style.setProperty('--fh-bg', d.bg);

                /* Headline */
                hlEl.textContent = '';
                var parts = d.headline.split('\n');
                if (parts.length > 1) {
                    hlEl.innerHTML = parts[0] + '<br>' + parts[1];
                } else {
                    hlEl.textContent = parts[0];
                }

                /* Animated text swap */
                if (animate) {
                    [originEl, styleEl, descEl].forEach(function(el) {
                        el.classList.remove('fh-anim-in');
                        /* Force reflow so animation restarts */
                        void el.offsetWidth;
                        el.classList.add('fh-anim-in');
                    });
                }
                originEl.textContent = d.origin;
                styleEl.textContent = d.style;
                descEl.textContent = d.desc;
            }

            /* ── Go to a specific slide index ─────────────── */
            function goTo(idx, instant) {
                if (busy && !instant) return;
                busy = true;

                current = ((idx % TOTAL) + TOTAL) % TOTAL;

                applyTheme(current, !instant);
                layout(current);
                updateDots(current);

                /* Unlock after transition duration */
                setTimeout(function() {
                    busy = false;
                }, instant ? 0 : 780);
            }

            /* ── Button listeners ──────────────────────────── */
            prevBtn.addEventListener('click', function() {
                goTo(current - 1);
            });
            nextBtn.addEventListener('click', function() {
                goTo(current + 1);
            });

            /* ── Dot listeners ─────────────────────────────── */
            dots.forEach(function(dot, i) {
                dot.addEventListener('click', function() {
                    goTo(i);
                });
            });

            /* ── Touch swipe support ───────────────────────── */
            var txStart = 0;
            var tyStart = 0;
            hero.addEventListener('touchstart', function(e) {
                txStart = e.changedTouches[0].clientX;
                tyStart = e.changedTouches[0].clientY;
            }, {
                passive: true
            });
            hero.addEventListener('touchend', function(e) {
                var dx = e.changedTouches[0].clientX - txStart;
                var dy = e.changedTouches[0].clientY - tyStart;
                /* Only trigger on horizontal swipes */
                if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 50) {
                    goTo(dx < 0 ? current + 1 : current - 1);
                }
            }, {
                passive: true
            });

            /* ── Keyboard ──────────────────────────────────── */
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft') goTo(current - 1);
                if (e.key === 'ArrowRight') goTo(current + 1);
            });

            /* ── Initialise ────────────────────────────────── */
            goTo(0, true);

        }());
    </script>

</body>

</html>