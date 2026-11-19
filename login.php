<?php
session_start();
include "db.php";

// ── Only handle POST requests ──────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');

// ── Basic input guard ──────────────────────────────────
if (empty($email) || empty($password)) {
    redirectWithError('Email and password are required.');
}

$sql  = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// ── No user found ──────────────────────────────────────
if ($result->num_rows !== 1) {
    redirectWithError('No account found with that email address.');
}

$user = $result->fetch_assoc();

// ── Account lock check (3 failed attempts → 3 min lock) ─
if ($user['failed_attempts'] >= 3) {

    $lock_sql  = "SELECT TIMESTAMPDIFF(SECOND, last_failed_login, NOW()) AS seconds_passed
                  FROM users WHERE email = ?";
    $lock_stmt = $conn->prepare($lock_sql);
    $lock_stmt->bind_param("s", $email);
    $lock_stmt->execute();
    $lock_data = $lock_stmt->get_result()->fetch_assoc();

    $seconds_left = 180 - (int)$lock_data['seconds_passed'];

    if ($seconds_left > 0) {
        $mins = ceil($seconds_left / 60);
        showErrorPage(
            'Account Temporarily Locked',
            "Too many failed attempts. Please try again in <strong>{$mins} minute" . ($mins > 1 ? 's' : '') . "</strong>.",
            'locked'
        );
    }

    // Lock expired — reset counter
    $reset_stmt = $conn->prepare("UPDATE users SET failed_attempts = 0, last_failed_login = NULL WHERE email = ?");
    $reset_stmt->bind_param("s", $email);
    $reset_stmt->execute();
    $user['failed_attempts'] = 0;
}

// ── Password check ─────────────────────────────────────
if (password_verify($password, $user['password'])) {

    // Success — reset failed attempts
    $reset_stmt = $conn->prepare("UPDATE users SET failed_attempts = 0, last_failed_login = NULL WHERE email = ?");
    $reset_stmt->bind_param("s", $email);
    $reset_stmt->execute();

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user']    = $user['first_name'];
    $_SESSION['role']    = $user['role'];

    header("Location: index.php");
    exit();

} else {

    // Wrong password — increment counter
    $new_attempts = $user['failed_attempts'] + 1;
    $update_stmt  = $conn->prepare("UPDATE users SET failed_attempts = ?, last_failed_login = NOW() WHERE email = ?");
    $update_stmt->bind_param("is", $new_attempts, $email);
    $update_stmt->execute();

    $remaining = 3 - $new_attempts;

    if ($new_attempts >= 3) {
        showErrorPage(
            'Account Locked',
            'Too many wrong attempts. Your account has been locked for <strong>3 minutes</strong>.',
            'locked'
        );
    } else {
        showErrorPage(
            'Incorrect Password',
            "The password you entered is wrong. You have <strong>{$remaining} attempt" . ($remaining > 1 ? 's' : '') . "</strong> remaining.",
            'wrong'
        );
    }
}

// ════════════════════════════════════════════════════════
//  HELPERS
// ════════════════════════════════════════════════════════

/** Redirect back to home with a flash message in session */
function redirectWithError($msg) {
    $_SESSION['login_error'] = $msg;
    header("Location: index.php");
    exit();
}

/** Render a styled, branded error page */
function showErrorPage($title, $body, $type = 'wrong') {

    $icon        = $type === 'locked' ? '🔒' : '⚠️';
    $accent      = $type === 'locked' ? '#c94f20' : '#e8a040';
    $accentLight = $type === 'locked' ? 'rgba(201,79,32,0.18)' : 'rgba(232,160,64,0.18)';

    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> — FoodFusion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #0d0d0d;
            color: #f0ece4;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Navbar ──────────────────────────────── */
        .nav-bar {
            width: 100%;
            background: rgba(10,10,10,0.95);
            border-bottom: 1px solid rgba(255,255,255,0.07);
            backdrop-filter: blur(16px);
        }
        .nav-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
        }
        .nav-logo {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 1.55rem;
            letter-spacing: 0.08em;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-logo-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: linear-gradient(135deg,#e8a040,#c94f20);
            display: inline-block;
        }

        /* ── Page body ───────────────────────────── */
        .page {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            background:
                radial-gradient(ellipse at 20% 50%, rgba(201,79,32,0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 30%, rgba(232,160,64,0.06) 0%, transparent 55%),
                #0d0d0d;
        }

        /* ── Card ────────────────────────────────── */
        .card {
            width: 100%;
            max-width: 460px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            padding: 48px 40px 40px;
            box-shadow: 0 32px 80px rgba(0,0,0,0.5);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            text-align: center;
            animation: cardIn 0.5s cubic-bezier(.4,0,.2,1) both;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(28px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0)    scale(1); }
        }

        /* Icon circle */
        .card-icon {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: <?php echo $accentLight; ?>;
            border: 1.5px solid <?php echo $accent; ?>44;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 24px;
        }

        /* Brand label */
        .card-brand {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 0.65rem;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .card-brand::before,
        .card-brand::after {
            content: '';
            display: block;
            width: 24px; height: 1px;
            background: currentColor;
        }

        /* Title */
        .card-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2rem;
            letter-spacing: 0.06em;
            color: #fff;
            margin-bottom: 14px;
            line-height: 1.1;
        }

        /* Body text */
        .card-body {
            font-size: 0.9rem;
            font-weight: 300;
            line-height: 1.75;
            color: rgba(255,255,255,0.6);
            margin-bottom: 32px;
        }
        .card-body strong {
            color: <?php echo $accent; ?>;
            font-weight: 600;
        }

        /* Accent bar */
        .card-bar {
            width: 48px; height: 3px;
            border-radius: 2px;
            background: linear-gradient(90deg, <?php echo $accent; ?>, <?php echo $accent; ?>88);
            margin: 0 auto 28px;
        }

        /* Buttons */
        .btn-row {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 13px 24px;
            border-radius: 10px;
            font-size: 0.84rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            border: none;
            transition: all 0.22s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, <?php echo $accent; ?>, #c94f20);
            color: #fff;
            box-shadow: 0 6px 20px rgba(201,79,32,0.3);
        }
        .btn-primary:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
            box-shadow: 0 10px 28px rgba(201,79,32,0.4);
        }
        .btn-ghost {
            background: rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.65);
            border: 1px solid rgba(255,255,255,0.12);
        }
        .btn-ghost:hover {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border-color: rgba(255,255,255,0.25);
        }

        /* Countdown timer (for locked state) */
        .countdown {
            display: inline-block;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.5rem;
            letter-spacing: 0.08em;
            color: <?php echo $accent; ?>;
            margin: 8px 0 24px;
            line-height: 1;
        }
        .countdown-label {
            font-size: 0.7rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            margin-bottom: 28px;
        }

        @media (max-width: 480px) {
            .card { padding: 36px 24px 32px; }
            .card-title { font-size: 1.7rem; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <header class="nav-bar">
        <div class="nav-inner">
            <a href="index.php" class="nav-logo">
                <span class="nav-logo-dot"></span>
                FoodFusion
            </a>
        </div>
    </header>

    <!-- Error card -->
    <main class="page">
        <div class="card" role="alert" aria-live="assertive">

            <div class="card-icon"><?php echo $icon; ?></div>
            <div class="card-brand">Food Fusion</div>
            <div class="card-bar"></div>
            <h1 class="card-title"><?php echo htmlspecialchars($title); ?></h1>
            <p class="card-body"><?php echo $body; ?></p>

            <?php if ($type === 'locked'): ?>
                <div class="countdown" id="cdTimer">3:00</div>
                <div class="countdown-label">Time remaining</div>
            <?php endif; ?>

            <div class="btn-row">
                <?php if ($type !== 'locked'): ?>
                    <a href="index.php" class="btn btn-primary">Try Again</a>
                    <a href="index.php" class="btn btn-ghost">← Back to Home</a>
                <?php else: ?>
                    <a href="index.php" class="btn btn-ghost">← Back to Home</a>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <?php if ($type === 'locked'): ?>
    <script>
        /* Countdown timer — 3 minutes then auto-redirect */
        var total = 180;
        var el    = document.getElementById('cdTimer');
        var tick  = setInterval(function () {
            total--;
            if (total <= 0) {
                clearInterval(tick);
                window.location.href = 'index.php';
                return;
            }
            var m = Math.floor(total / 60);
            var s = total % 60;
            el.textContent = m + ':' + (s < 10 ? '0' : '') + s;
        }, 1000);
    </script>
    <?php endif; ?>

</body>
</html>
    <?php
    exit();
}
?>
