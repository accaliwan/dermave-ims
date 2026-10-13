<?php
session_start();
require_once __DIR__ . '/includes/config.php';

// Already logged in? Go to the dashboard.
if (!empty($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // TODO: replace with a database lookup + password_verify()
    if ($username === DEMO_USER && $password === DEMO_PASS) {
        session_regenerate_id(true);
        $_SESSION['user'] = $username;
        header('Location: dashboard.php');
        exit;
    }

    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | DermAve IMS</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">

  <main class="login-card">
    <!-- TODO: swap for <img src="assets/img/logo.png"> once the logo file is added -->
    <div class="brand">
      <div class="brand__name">DERM<span>AVE</span></div>
      <div class="brand__tag">Medical Aesthetic &amp; Wellness Clinic</div>
    </div>

    <h1 class="login-card__title">Inventory Management System</h1>

    <?php if ($error): ?>
      <div class="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form id="loginForm" method="POST" action="login.php" novalidate>
      <div class="form-group">
        <label for="username">Login</label>
        <input type="text" id="username" name="username" placeholder="Username" autocomplete="username" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <div class="password-wrap">
          <input type="password" id="password" name="password" placeholder="Password" autocomplete="current-password" required>
          <button type="button" id="togglePassword">Show</button>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn-primary btn--primary">Sign In</button>
        <button type="button" class="btn btn--ghost" id="forgotBtn">Forgot?</button>
      </div>
    </form>
  </main>

  <script src="assets/js/login.js"></script>
</body>
</html>
