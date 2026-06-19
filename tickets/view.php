<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config.php';

require_login();

$ticket_id = $_GET['id'] ?? '';

if (!ctype_digit((string)$ticket_id) || (int)$ticket_id === 0) {
    header('Location: /dashboard/user.php');
    exit();
}

/* ── POST: add comment ── */
$comment_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $body = trim($_POST['body'] ?? '');
    if ($body === '') {
        $comment_error = 'Comment cannot be empty.';
    } elseif (strlen($body) > 2000) {
        $comment_error = 'Comment must be 2000 characters or fewer.';
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO comments (ticket_id, user_id, body) VALUES (:ticket_id, :user_id, :body)'
        );
        $stmt->execute([
            ':ticket_id' => (int)$ticket_id,
            ':user_id'   => (int)$_SESSION['user_id'],
            ':body'      => $body,
        ]);
        $_SESSION['flash_success'] = 'Comment posted.';
        header('Location: /tickets/view.php?id=' . (int)$ticket_id);
        exit();
    }
}

/* ── Fetch ticket + author ── */
$stmt = $pdo->prepare(
    'SELECT t.*, u.username AS author
     FROM tickets t
     JOIN users u ON t.user_id = u.id
     WHERE t.id = :id'
);
$stmt->execute([':id' => (int)$ticket_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <h1 style="font-size:32px;font-weight:700;letter-spacing:-0.02em;margin-bottom:10px;color:var(--text);">Not found.</h1>
    <p class="text-muted" style="font-size:14px;line-height:1.6;margin-bottom:24px;">
      This ticket doesn&rsquo;t exist.
    </p>
    <a href="/dashboard/user.php" class="btn btn-primary">Back to dashboard</a>
  </div>
</body>
</html>
<?php
    exit();
endif;

/* ── Fetch comments + author names ── */
$stmt = $pdo->prepare(
    'SELECT c.id, c.body, c.created_at, u.username AS author
     FROM comments c
     JOIN users u ON c.user_id = u.id
     WHERE c.ticket_id = :ticket_id
     ORDER BY c.created_at ASC'
);
$stmt->execute([':ticket_id' => (int)$ticket_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ── Helpers ── */
function view_time_ago(string $datetime): string {
    $diff = (new DateTime())->diff(new DateTime($datetime));
    if ($diff->days > 6)  return (new DateTime($datetime))->format('M j');
    if ($diff->days > 0)  return $diff->days . 'd ago';
    if ($diff->h   > 0)   return $diff->h    . 'h ago';
    if ($diff->i   > 0)   return $diff->i    . 'm ago';
    return 'just now';
}

$tid          = 'TH-' . str_pad((int)$ticket_id, 3, '0', STR_PAD_LEFT);
$username     = $_SESSION['username'];
$display_name = is_admin() ? 'Admin' : $username;
$is_owner     = (int)$ticket['user_id'] === (int)$_SESSION['user_id'];

$status   = $ticket['status'];
$priority = $ticket['priority'];

$status_label = match($status) {
    'open'        => 'Open',
    'in-progress' => 'In Progress',
    'closed'      => 'Closed',
    default       => ucfirst($status),
};
$priority_label = ucfirst($priority);
$status_class   = $status === 'in-progress' ? 'progress' : $status;

$days_old  = (int)(new DateTime())->diff(new DateTime($ticket['created_at']))->days;
$age_class = '';
if ($status !== 'closed') {
    if ($days_old >= 3)     $age_class = 'age-stale';
    elseif ($days_old >= 1) $age_class = 'age-aging';
}
$age_label = view_time_ago($ticket['created_at']);

$flash = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($tid); ?> — TicketSystem</title>
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
        <div class="avatar"><?php echo htmlspecialchars(strtoupper(substr($username, 0, 2))); ?></div>
        <div>
          <div class="sidebar-user-name"><?php echo htmlspecialchars($display_name); ?></div>
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
        <div class="ticket-header-meta">
          <span class="ticket-id-badge"><?php echo $tid; ?></span>
          <span class="pill status-<?php echo htmlspecialchars($status_class); ?>">
            <span class="pill-dot"></span><?php echo htmlspecialchars($status_label); ?>
          </span>
          <span class="pill priority-<?php echo htmlspecialchars($priority); ?>">
            <span class="pill-dot"></span><?php echo htmlspecialchars($priority_label); ?>
          </span>
        </div>
        <h1 class="page-title"><?php echo htmlspecialchars($ticket['title']); ?></h1>
      </div>
      <?php if ($is_owner || is_admin()): ?>
      <a href="/tickets/edit.php?id=<?php echo (int)$ticket_id; ?>" class="btn btn-ghost">Edit ticket</a>
      <?php endif; ?>
    </div>

    <div class="ticket-view-wrap">
      <div class="ticket-view-grid">

        <!-- ── Left: description + comments ── -->
        <div>

          <!-- Description -->
          <div class="ticket-view-card">
            <div class="ticket-view-card-head">Description</div>
            <div class="ticket-view-desc"><?php echo htmlspecialchars($ticket['description']); ?></div>
          </div>

          <!-- Comments -->
          <div class="comments-section">
            <div class="comments-head">
              Comments
              <span class="comments-count"><?php echo count($comments); ?></span>
            </div>

            <?php if (!empty($comments)): ?>
            <div class="ticket-view-card" style="margin-bottom:20px;">
              <?php foreach ($comments as $comment): ?>
              <div class="comment">
                <div class="avatar" style="flex-shrink:0;"><?php echo htmlspecialchars(strtoupper(substr($comment['author'], 0, 2))); ?></div>
                <div class="comment-content">
                  <div class="comment-header">
                    <span class="comment-author"><?php echo htmlspecialchars($comment['author']); ?></span>
                    <span class="comment-time"><?php echo view_time_ago($comment['created_at']); ?></span>
                  </div>
                  <div class="comment-body"><?php echo htmlspecialchars($comment['body']); ?></div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Add comment -->
            <div class="comment-form">
              <div class="comment-form-head">Add a comment</div>
              <?php if ($comment_error): ?>
              <div class="alert-error" style="margin:16px 20px 0;"><?php echo htmlspecialchars($comment_error); ?></div>
              <?php endif; ?>
              <form method="POST" action="/tickets/view.php?id=<?php echo (int)$ticket_id; ?>">
                <div class="comment-form-body">
                  <textarea class="input" name="body" rows="4"
                    placeholder="Write your comment here&hellip;" maxlength="2000"><?php echo htmlspecialchars($_POST['body'] ?? ''); ?></textarea>
                </div>
                <div class="comment-form-footer">
                  <button type="submit" class="btn btn-primary">Post comment</button>
                </div>
              </form>
            </div>
          </div>

        </div>

        <!-- ── Right: meta card ── -->
        <div class="ticket-meta-card">
          <div class="ticket-meta-head">Details</div>

          <div class="ticket-meta-item">
            <span class="ticket-meta-label">Status</span>
            <span class="pill status-<?php echo htmlspecialchars($status_class); ?>">
              <span class="pill-dot"></span>
              <?php echo htmlspecialchars($status_label); ?>
            </span>
          </div>

          <div class="ticket-meta-item">
            <span class="ticket-meta-label">Priority</span>
            <span class="pill priority-<?php echo htmlspecialchars($priority); ?>">
              <span class="pill-dot"></span>
              <?php echo htmlspecialchars($priority_label); ?>
            </span>
          </div>

          <div class="ticket-meta-item">
            <span class="ticket-meta-label">Author</span>
            <span class="ticket-meta-value"><?php echo htmlspecialchars($ticket['author']); ?></span>
          </div>

          <div class="ticket-meta-item">
            <span class="ticket-meta-label">Created</span>
            <span class="ticket-meta-value <?php echo $age_class; ?>"><?php echo htmlspecialchars($age_label); ?></span>
          </div>

          <div class="ticket-meta-item">
            <span class="ticket-meta-label">Comments</span>
            <span style="font-family:var(--font-mono);font-size:13px;color:var(--text);"><?php echo count($comments); ?></span>
          </div>

          <?php if ($is_owner || is_admin()): ?>
          <div class="ticket-meta-actions">
            <a href="/tickets/edit.php?id=<?php echo (int)$ticket_id; ?>"
               class="btn btn-ghost btn-sm" style="width:100%;justify-content:center;">Edit ticket</a>
          </div>
          <?php endif; ?>
        </div>

      </div>
    </div>

  </main>
</div>

<?php if (is_admin()): ?>
<script src="/chat/chat.js"></script>
<?php endif; ?>
<script src="/public/js/canvas.js"></script>
<script src="/public/js/toast.js"></script>
<?php if ($flash): ?>
<script>showToast(<?php echo json_encode($flash); ?>, 'success');</script>
<?php endif; ?>
</body>
</html>
