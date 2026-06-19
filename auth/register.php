<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config.php';

if (is_logged_in()) {
    header('Location: /dashboard/user.php');
    exit();
}

$errors   = [];
$username = '';
$email    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($username === '') {
        $errors[] = 'Username is required.';
    } elseif (strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters.';
    } elseif (strlen($username) > 50) {
        $errors[] = 'Username cannot be longer than 50 characters.';
    }

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is not valid.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($confirm === '') {
        $errors[] = 'Please confirm your password.';
    } elseif ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT username, email FROM users WHERE username = :username OR email = :email LIMIT 1');
        $stmt->execute([':username' => $username, ':email' => $email]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Trust the DB uniqueness check — avoid PHP string comparison
            // which is case-sensitive while MySQL collation is case-insensitive
            if ($existing['username']) {
                $errors[] = 'That username is already taken.';
            }
            if ($existing['email']) {
                $errors[] = 'An account with that email already exists.';
            }
        }
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->execute([':username' => $username, ':email' => $email, ':password' => $hash]);

        $_SESSION['flash_success'] = 'Account created! You can now log in.';
        header('Location: /auth/login.php');
        exit();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create account — TicketSystem</title>
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

      <h1 class="auth-title">Create account</h1>
      <p class="auth-sub">Get started for free</p>

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

      <form method="POST" action="/auth/register.php">

        <div class="fields">

          <div class="form-group">
            <label class="label" for="username">Username</label>
            <input
              class="input"
              type="text"
              id="username"
              name="username"
              value="<?php echo htmlspecialchars($username); ?>"
              placeholder="yourname"
              autocomplete="username"
              minlength="3"
              maxlength="50"
              required>
          </div>

          <div class="form-group">
            <label class="label" for="email">Email</label>
            <input
              class="input"
              type="email"
              id="email"
              name="email"
              value="<?php echo htmlspecialchars($email); ?>"
              placeholder="you@example.com"
              autocomplete="email"
              required>
          </div>

          <div class="form-group">
            <label class="label" for="password">Password</label>
            <div class="password-wrap">
              <input
                class="input"
                type="password"
                id="password"
                name="password"
                placeholder="At least 8 characters"
                autocomplete="new-password"
                minlength="8"
                required>
              <button type="button" class="password-toggle" onclick="togglePw('password','pw-icon1')" aria-label="Show or hide password">
                <span id="pw-icon1"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
              </button>
            </div>
          </div>

          <div class="form-group">
            <label class="label" for="confirm_password">Confirm password</label>
            <div class="password-wrap">
              <input
                class="input"
                type="password"
                id="confirm_password"
                name="confirm_password"
                placeholder="Repeat your password"
                autocomplete="new-password"
                minlength="8"
                required>
              <button type="button" class="password-toggle" onclick="togglePw('confirm_password','pw-icon2')" aria-label="Show or hide password">
                <span id="pw-icon2"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
              </button>
            </div>
          </div>

        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block">Create account</button>

      </form>

      <p class="auth-footer-link">Already have an account? <a href="/auth/login.php">Sign in</a></p>

    </div>
  </div>

</div>

<script src="/public/js/auth.js"></script>
<script src="/public/js/canvas.js"></script>
</body>
</html>
