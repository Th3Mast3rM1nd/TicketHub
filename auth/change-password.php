<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config.php';

require_login();

$errors = [];

$flash = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['new_password']      ?? '';
    $confirm_password = $_POST['confirm_password']  ?? '';

    if ($current_password === '') {
        $errors[] = 'Current password is required.';
    }

    if ($new_password === '') {
        $errors[] = 'New password is required.';
    } elseif (strlen($new_password) < 8) {
        $errors[] = 'New password must be at least 8 characters.';
    }

    if ($confirm_password === '') {
        $errors[] = 'Please confirm your new password.';
    } elseif ($new_password !== $confirm_password) {
        $errors[] = 'New passwords do not match.';
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare('SELECT password FROM users WHERE id = :id LIMIT 1');
        // Cast to int to prevent type juggling attacks on the session value
        $stmt->execute([':id' => (int) $_SESSION['user_id']]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($current_password, $user['password'])) {
            $errors[] = 'Current password is incorrect.';
        } elseif ($new_password === $current_password) {
            $errors[] = 'New password must be different from your current password.';
        } else {
            $new_hash = password_hash($new_password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('UPDATE users SET password = :password WHERE id = :id');
            $stmt->execute([':password' => $new_hash, ':id' => (int) $_SESSION['user_id']]);

            $_SESSION['flash_success'] = 'Password changed successfully.';
            header('Location: /auth/change-password.php');
            exit();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change password — TicketSystem</title>
  <link rel="stylesheet" href="/public/css/app.css">
  <script src="/public/js/theme.js"></script>
  <link rel="stylesheet" href="/public/css/auth.css">
</head>
<body>

<div class="auth-wrap">

  <!-- Left brand panel -->
  <div class="auth-brand">
    <div class="auth-brand-inner">

      <div class="brand-logo-wrap">
        <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="8" y="16" width="48" height="36" rx="6" stroke="white" stroke-width="3" fill="none"/>
          <path d="M8 26h48" stroke="white" stroke-width="3"/>
          <rect x="16" y="34" width="12" height="4" rx="2" fill="white"/>
          <rect x="16" y="42" width="20" height="4" rx="2" fill="white"/>
        </svg>
      </div>

      <h2 class="brand-title">Ticketsystem</h2>
      <p class="brand-tagline">Manage tickets. Stay organized.</p>

      <hr class="brand-divider">

      <ul class="brand-benefits">
        <li>
          <svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 9l4 4 8-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Track every request in one place
        </li>
        <li>
          <svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 9l4 4 8-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          AI assistant built in
        </li>
        <li>
          <svg viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 9l4 4 8-8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Open source &amp; self-hosted
        </li>
      </ul>

      <div class="brand-testimonial">
        <img
          src="https://randomuser.me/api/portraits/women/44.jpg"
          alt="Sarah K."
          class="brand-testimonial-avatar"
          loading="lazy"
        >
        <div class="brand-testimonial-body">
          <p class="brand-testimonial-quote">"The AI assistant cut our response time in half."</p>
          <p class="brand-testimonial-name">Sarah K., IT Manager</p>
        </div>
      </div>

    </div>
  </div>

  <!-- Right form panel -->
  <div class="auth-panel">
    <div class="auth-card">

      <h1 class="auth-title">Change password</h1>
      <p class="auth-sub">Enter your current password and choose a new one</p>

      <?php if (!empty($errors)): ?>
        <div class="alert-error">
          <?php if (count($errors) === 1): ?>
            <?php echo htmlspecialchars($errors[0]); ?>
          <?php else: ?>
            <ul>
              <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if ($flash): ?>
        <div class="alert-success"><?php echo htmlspecialchars($flash); ?></div>
      <?php endif; ?>

      <form method="POST" action="/auth/change-password.php">

        <div class="fields">

          <div class="form-group">
            <label class="label" for="current_password">Current password</label>
            <div class="password-wrap">
              <input
                class="input"
                type="password"
                id="current_password"
                name="current_password"
                placeholder="Enter current password"
                autocomplete="current-password"
                required>
              <button type="button" class="password-toggle" onclick="togglePw('current_password','pw-icon1')" aria-label="Show or hide password">
                <span id="pw-icon1"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
              </button>
            </div>
          </div>

          <div class="form-group">
            <label class="label" for="new_password">New password</label>
            <div class="password-wrap">
              <input
                class="input"
                type="password"
                id="new_password"
                name="new_password"
                placeholder="At least 8 characters"
                autocomplete="new-password"
                minlength="8"
                required>
              <button type="button" class="password-toggle" onclick="togglePw('new_password','pw-icon2')" aria-label="Show or hide password">
                <span id="pw-icon2"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
              </button>
            </div>
          </div>

          <div class="form-group">
            <label class="label" for="confirm_password">Confirm new password</label>
            <div class="password-wrap">
              <input
                class="input"
                type="password"
                id="confirm_password"
                name="confirm_password"
                placeholder="Repeat new password"
                autocomplete="new-password"
                minlength="8"
                required>
              <button type="button" class="password-toggle" onclick="togglePw('confirm_password','pw-icon3')" aria-label="Show or hide password">
                <span id="pw-icon3"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
              </button>
            </div>
          </div>

        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block">Update password</button>

      </form>

      <p class="auth-footer-link"><a href="/dashboard/user.php">← Back to dashboard</a></p>

    </div>
  </div>

</div>

<script src="/public/js/auth.js"></script>
<script src="/public/js/canvas.js"></script>
</body>
</html>
