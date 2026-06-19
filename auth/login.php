<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config.php';

if (is_logged_in()) {
    header('Location: /dashboard/user.php');
    exit();
}

$errors = [];
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = trim($_POST['email']    ?? '');
    $password = $_POST['password']      ?? '';

    if ($email === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is not valid.';
    }

    if ($password === '') {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare('SELECT id, username, password, role FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {

            session_regenerate_id(true);
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            header('Location: /dashboard/user.php');
            exit();

        } else {
            $errors[] = 'Invalid email or password.';
        }
    }
}

$flash = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in — TicketSystem</title>
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

      <h1 class="auth-title">Welcome back</h1>
      <p class="auth-sub">Sign in to your account</p>

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

      <form method="POST" action="/auth/login.php">

        <div class="fields">

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
                placeholder="Enter your password"
                autocomplete="current-password"
                required>
              <button type="button" class="password-toggle" onclick="togglePw('password','pw-icon1')" aria-label="Show or hide password">
                <span id="pw-icon1"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></span>
              </button>
            </div>
          </div>

        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>

      </form>

      <p class="auth-footer-link">Don't have an account? <a href="/auth/register.php">Create one</a></p>

    </div>
  </div>

</div>

<script src="/public/js/auth.js"></script>
<script src="/public/js/canvas.js"></script>
</body>
</html>
