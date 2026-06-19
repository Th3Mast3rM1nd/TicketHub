<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config.php';

require_login();

$ticket_id = $_GET['id'] ?? '';

if (!ctype_digit((string)$ticket_id)) {
    header('Location: /dashboard/user.php');
    exit();
}

$allowed_statuses   = ['open', 'in-progress', 'closed'];
$allowed_priorities = ['low', 'medium', 'high'];

if (is_admin()) {
    $stmt = $pdo->prepare('SELECT * FROM tickets WHERE id = :id');
    $stmt->execute([':id' => $ticket_id]);
} else {
    $stmt = $pdo->prepare('SELECT * FROM tickets WHERE id = :id AND user_id = :user_id');
    $stmt->execute([':id' => $ticket_id, ':user_id' => $_SESSION['user_id']]);
}
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

/* ── Not found / no permission ── */
if (!$ticket):
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Not Found — TicketSystem</title>
  <link rel="stylesheet" href="/public/css/app.css">
  <script src="/public/js/theme.js"></script>
</head>
<body style="display:flex;align-items:center;justify-content:center;min-height:100vh;">
  <div style="text-align:center;max-width:400px;padding:24px;">
    <div class="text-subtle" style="margin-bottom:20px;opacity:0.4;">
      <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
    </div>
    <h1 style="font-size:32px;font-weight:700;letter-spacing:-0.02em;margin-bottom:10px;color:var(--text);">Not found.</h1>
    <p class="text-muted" style="font-size:14px;line-height:1.6;margin-bottom:24px;">
      This ticket doesn&rsquo;t exist or you don&rsquo;t have permission to view it.
    </p>
    <a href="/dashboard/user.php" class="btn btn-primary">Back to dashboard</a>
  </div>
  <script src="/public/js/canvas.js"></script>
</body>
</html>
<?php
    exit();
endif;

/* ── Edit form logic ── */
$errors      = [];
$title       = $ticket['title'];
$description = $ticket['description'];
$status      = $ticket['status'];
$priority    = $ticket['priority'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = trim($_POST['title']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $status      = $_POST['status']            ?? '';
    $priority    = $_POST['priority']          ?? '';

    if ($title === '') {
        $errors[] = 'Title is required.';
    } elseif (strlen($title) > 100) {
        $errors[] = 'Title must be 100 characters or fewer.';
    }

    if ($description === '') {
        $errors[] = 'Description is required.';
    }

    if (!in_array($status, $allowed_statuses, true)) {
        $errors[] = 'Invalid status.';
    }

    if (!in_array($priority, $allowed_priorities, true)) {
        $errors[] = 'Invalid priority.';
    }

    if (empty($errors)) {
        if (is_admin()) {
            $stmt = $pdo->prepare(
                'UPDATE tickets SET title = :title, description = :description,
                 status = :status, priority = :priority
                 WHERE id = :id'
            );
            $stmt->execute([
                ':title'       => $title,
                ':description' => $description,
                ':status'      => $status,
                ':priority'    => $priority,
                ':id'          => $ticket_id,
            ]);
        } else {
            $stmt = $pdo->prepare(
                'UPDATE tickets SET title = :title, description = :description,
                 status = :status, priority = :priority
                 WHERE id = :id AND user_id = :user_id'
            );
            $stmt->execute([
                ':title'       => $title,
                ':description' => $description,
                ':status'      => $status,
                ':priority'    => $priority,
                ':id'          => $ticket_id,
                ':user_id'     => $_SESSION['user_id'],
            ]);
        }

        $_SESSION['flash_success'] = 'Ticket updated successfully.';
        header('Location: /dashboard/user.php');
        exit();
    }
}

/* Format the ticket ID the same way the dashboard does */
$tid = 'TH-' . str_pad((int)$ticket_id, 3, '0', STR_PAD_LEFT);
?>
<?php $__u = $_SESSION['username'] ?? ''; $__dn = is_admin() ? 'Admin' : $__u; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit <?php echo htmlspecialchars($tid); ?> — TicketSystem</title>
  <link rel="stylesheet" href="/public/css/app.css">
  <script src="/public/js/theme.js"></script>
  <link rel="stylesheet" href="/public/css/dashboard.css">
</head>
<body>

<div class="dash">

  <!-- ═══ Sidebar ═══ -->
  <aside class="sidebar">
    <div class="sidebar-logo">
      <span class="sidebar-logo-mark"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:block"><path d="M3 4 L9 4 Q12 7 15 4 L21 4 Q23 4 23 6 L23 18 Q23 20 21 20 L15 20 Q12 17 9 20 L3 20 Q1 20 1 18 L1 6 Q1 4 3 4 Z"/><polyline points="8,13 11,16 16.5,9"/></svg></span>
      Ticketsystem
    </div>

    <div class="sidebar-section">Workspace</div>

    <a href="/dashboard/user.php" class="sidebar-link">
      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
      Tickets
    </a>

    <div class="sidebar-section">Account</div>

    <a href="/auth/change-password.php" class="sidebar-link">
      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      Change password
    </a>

    <?php if (is_admin()): ?>
    <a href="/admin/index.php" class="sidebar-link">
      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Admin panel
    </a>
    <?php endif; ?>

    <div class="sidebar-bottom">
      <a href="/auth/logout.php" class="sidebar-user">
        <div class="avatar"><?php echo htmlspecialchars(strtoupper(substr($__u, 0, 2))); ?></div>
        <div>
          <div class="sidebar-user-name"><?php echo htmlspecialchars($__dn); ?></div>
          <div class="sidebar-user-role">Sign out &rarr;</div>
        </div>
      </a>
    </div>
  </aside>

  <!-- ═══ Main ═══ -->
  <main class="main">

    <div class="main-head">
      <div>
        <a href="/dashboard/user.php" class="page-back">&larr; Dashboard</a>
        <h1 class="page-title">Edit ticket.</h1>
      </div>
    </div>

    <div class="form-wrap">

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

      <div class="form-card">
        <form id="edit-form" method="POST" action="/tickets/edit.php?id=<?php echo (int)$ticket_id; ?>">
          <div class="form-card-body">

            <div class="form-group">
              <label class="label" for="title">Title</label>
              <input class="input" type="text" id="title" name="title"
                value="<?php echo htmlspecialchars($title); ?>"
                placeholder="Brief summary of the issue"
                maxlength="100" required>
            </div>

            <div class="form-group">
              <label class="label" for="description">Description</label>
              <textarea class="input" id="description" name="description"
                placeholder="Describe the issue in detail&hellip;" required><?php echo htmlspecialchars($description); ?></textarea>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label class="label" for="status">Status</label>
                <select class="input" id="status" name="status">
                  <option value="open"        <?php if ($status === 'open')        echo 'selected'; ?>>Open</option>
                  <option value="in-progress" <?php if ($status === 'in-progress') echo 'selected'; ?>>In Progress</option>
                  <option value="closed"      <?php if ($status === 'closed')      echo 'selected'; ?>>Closed</option>
                </select>
              </div>
              <div class="form-group">
                <label class="label" for="priority">Priority</label>
                <select class="input" id="priority" name="priority">
                  <option value="low"    <?php if ($priority === 'low')    echo 'selected'; ?>>Low</option>
                  <option value="medium" <?php if ($priority === 'medium') echo 'selected'; ?>>Medium</option>
                  <option value="high"   <?php if ($priority === 'high')   echo 'selected'; ?>>High</option>
                </select>
              </div>
            </div>

          </div>
        </form>
        <div class="form-card-footer">
          <button type="submit" form="edit-form" class="btn btn-primary">Save changes</button>
          <a href="/dashboard/user.php" class="btn btn-ghost">Cancel</a>
          <form method="POST" action="/tickets/delete.php" style="margin-left:auto"
            onsubmit="return confirm('Delete this ticket?')">
            <input type="hidden" name="id" value="<?php echo (int)$ticket['id']; ?>">
            <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </div>
      </div>

    </div>

  </main>
</div>

<script src="/public/js/canvas.js"></script>
</body>
</html>
