<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config.php';

require_admin();

$me = (int)$_SESSION['user_id'];

/* ── Handle POST actions ── */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action  = $_POST['action']  ?? '';
    $user_id = $_POST['user_id'] ?? '';

    if (!ctype_digit((string)$user_id)) {
        header('Location: /admin/users.php');
        exit();
    }
    $user_id = (int)$user_id;

    if ($action === 'set_role') {
        $role = $_POST['role'] ?? '';
        if (in_array($role, ['user', 'admin'], true) && $user_id !== $me) {
            /* Prevent removing the last admin */
            if ($role === 'user') {
                $stmt = $pdo->query('SELECT COUNT(*) FROM users WHERE role = \'admin\'');
                $admin_count = (int)$stmt->fetchColumn();
                if ($admin_count <= 1) {
                    $_SESSION['flash_error'] = 'Cannot remove the last admin.';
                    header('Location: /admin/users.php');
                    exit();
                }
            }
            $stmt = $pdo->prepare('UPDATE users SET role = :role WHERE id = :id');
            $stmt->execute([':role' => $role, ':id' => $user_id]);
            $_SESSION['flash_success'] = 'Role updated.';
        }
    } elseif ($action === 'delete') {
        if ($user_id !== $me) {
            /* Prevent deleting the last admin */
            $stmt = $pdo->prepare('SELECT role FROM users WHERE id = :id');
            $stmt->execute([':id' => $user_id]);
            $target = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($target && $target['role'] === 'admin') {
                $stmt = $pdo->query('SELECT COUNT(*) FROM users WHERE role = \'admin\'');
                if ((int)$stmt->fetchColumn() <= 1) {
                    $_SESSION['flash_error'] = 'Cannot delete the last admin.';
                    header('Location: /admin/users.php');
                    exit();
                }
            }

            $stmt = $pdo->prepare('DELETE FROM users WHERE id = :id');
            $stmt->execute([':id' => $user_id]);
            if ($stmt->rowCount() > 0) {
                $_SESSION['flash_success'] = 'User deleted.';
            }
        }
    }

    header('Location: /admin/users.php');
    exit();
}

/* ── Fetch all users + ticket count ── */
$stmt = $pdo->query(
    'SELECT u.id, u.username, u.email, u.role, u.created_at,
            COUNT(t.id) AS ticket_count
     FROM users u
     LEFT JOIN tickets t ON t.user_id = u.id
     GROUP BY u.id
     ORDER BY u.created_at ASC'
);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error   = $_SESSION['flash_error']   ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

function user_time_ago(string $datetime): string {
    $diff = (new DateTime())->diff(new DateTime($datetime));
    if ($diff->days > 30) return (new DateTime($datetime))->format('M j, Y');
    if ($diff->days > 0)  return $diff->days . 'd ago';
    if ($diff->h   > 0)   return $diff->h    . 'h ago';
    return 'today';
}

$count_admin = count(array_filter($users, fn($u) => $u['role'] === 'admin'));
$count_user  = count(array_filter($users, fn($u) => $u['role'] === 'user'));
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users — TicketSystem</title>
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

    <div class="sidebar-section">Admin</div>

    <a href="/admin/index.php" class="sidebar-link">
      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Tickets
    </a>

    <a href="/admin/users.php" class="sidebar-link active">
      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Users
      <span class="sidebar-count"><?php echo count($users); ?></span>
    </a>

    <div class="sidebar-bottom">
      <a href="/auth/logout.php" class="sidebar-user">
        <div class="avatar"><?php echo htmlspecialchars(strtoupper(substr($_SESSION['username'] ?? '', 0, 2))); ?></div>
        <div>
          <div class="sidebar-user-name">Admin</div>
          <div class="sidebar-user-role">Sign out &rarr;</div>
        </div>
      </a>
    </div>
  </aside>

  <!-- ═══ Main ═══ -->
  <main class="main">

    <div class="main-head">
      <div>
        <h1 class="page-title">Users.</h1>
        <p class="page-sub"><?php echo count($users); ?> registered &mdash; <?php echo $count_admin; ?> admin<?php echo $count_admin !== 1 ? 's' : ''; ?>, <?php echo $count_user; ?> user<?php echo $count_user !== 1 ? 's' : ''; ?>.</p>
      </div>
    </div>


    <!-- Search -->
    <div class="tickets-toolbar">
      <input class="input" id="user-search" type="search" placeholder="Search users&hellip;" style="max-width:260px">
      <select class="input" id="user-filter-role" style="max-width:160px">
        <option value="">All roles</option>
        <option value="admin">Admin</option>
        <option value="user">User</option>
      </select>
    </div>

    <table class="tickets-table">
      <thead>
        <tr>
          <th>User</th>
          <th>Email</th>
          <th>Role</th>
          <th>Tickets</th>
          <th>Joined</th>
          <th></th>
        </tr>
      </thead>
      <tbody>

        <?php foreach ($users as $user):
          $uid      = (int)$user['id'];
          $is_me    = $uid === $me;
          $is_admin = $user['role'] === 'admin';
        ?>
        <tr class="ticket-row user-row"
            data-username="<?php echo htmlspecialchars(strtolower($user['username'])); ?>"
            data-email="<?php echo htmlspecialchars(strtolower($user['email'])); ?>"
            data-role="<?php echo htmlspecialchars($user['role']); ?>">

          <!-- User cell: avatar + username -->
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div class="avatar" style="<?php echo $is_admin ? 'background:rgba(79,70,229,0.18);color:var(--brand);' : ''; ?>">
                <?php echo htmlspecialchars(strtoupper(substr($user['username'], 0, 2))); ?>
              </div>
              <div>
                <div style="font-size:13px;font-weight:500;color:var(--text);">
                  <?php echo htmlspecialchars($user['username']); ?>
                  <?php if ($is_me): ?>
                  <span style="font-size:11px;color:var(--text-subtle);font-family:var(--font-mono);margin-left:4px;">(you)</span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </td>

          <td style="font-size:13px;color:var(--text-muted);"><?php echo htmlspecialchars($user['email']); ?></td>

          <td>
            <?php if ($is_me): ?>
            <span class="pill <?php echo $is_admin ? 'pill-brand' : 'status-open'; ?>">
              <span class="pill-dot"></span>
              <?php echo $is_admin ? 'Admin' : 'User'; ?>
            </span>
            <?php else: ?>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="action" value="set_role">
              <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
              <select name="role" class="input" style="max-width:120px;padding:4px 8px;font-size:12px;" onchange="this.form.submit()">
                <option value="user"  <?php if (!$is_admin) echo 'selected'; ?>>User</option>
                <option value="admin" <?php if ($is_admin)  echo 'selected'; ?>>Admin</option>
              </select>
            </form>
            <?php endif; ?>
          </td>

          <td style="font-family:var(--font-mono);font-size:13px;color:var(--text);"><?php echo (int)$user['ticket_count']; ?></td>

          <td style="font-size:13px;color:var(--text-muted);"><?php echo user_time_ago($user['created_at']); ?></td>

          <td>
            <?php if (!$is_me): ?>
            <form method="POST"
              onsubmit="return confirm('Delete <?php echo htmlspecialchars($user['username'], ENT_QUOTES); ?>? All their tickets and comments will be removed.')">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
              <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
            <?php endif; ?>
          </td>

        </tr>
        <?php endforeach; ?>

        <tr class="user-empty" style="display:none">
          <td colspan="6"><div class="empty-state">No users found.</div></td>
        </tr>

      </tbody>
    </table>

  </main>
</div>

<script>
  (function () {
    var search  = document.getElementById('user-search');
    var fRole   = document.getElementById('user-filter-role');
    var rows    = document.querySelectorAll('.user-row');
    var empty   = document.querySelector('.user-empty');

    function applyFilters() {
      var q    = search.value.toLowerCase();
      var role = fRole.value;
      var visible = 0;

      rows.forEach(function (row) {
        var show = (!q    || row.dataset.username.includes(q) || row.dataset.email.includes(q))
                && (!role || row.dataset.role === role);
        row.style.display = show ? '' : 'none';
        if (show) visible++;
      });

      if (empty) empty.style.display = visible === 0 ? '' : 'none';
    }

    search.addEventListener('input', applyFilters);
    fRole.addEventListener('change', applyFilters);
  }());
</script>

<script src="/public/js/toast.js"></script>
<?php if ($flash_success): ?>
<script>showToast(<?php echo json_encode($flash_success); ?>, 'success');</script>
<?php endif; ?>
<?php if ($flash_error): ?>
<script>showToast(<?php echo json_encode($flash_error); ?>, 'error');</script>
<?php endif; ?>
<script src="/chat/chat.js"></script>
<script src="/public/js/canvas.js"></script>
</body>
</html>
