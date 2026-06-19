<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config.php';

require_login();

$username     = $_SESSION['username'];
$display_name = is_admin() ? 'Admin' : $username;

$stmt = $pdo->query(
    'SELECT t.id, t.title, t.status, t.priority, t.created_at, u.username AS author, t.user_id
     FROM tickets t
     JOIN users u ON t.user_id = u.id
     ORDER BY t.created_at DESC'
);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Stat cards: scoped to logged-in user's tickets */
$stmt_us = $pdo->prepare(
    'SELECT status, COUNT(*) AS cnt FROM tickets WHERE user_id = :uid GROUP BY status'
);
$stmt_us->execute([':uid' => (int)$_SESSION['user_id']]);
$stats = ['total' => 0, 'open' => 0, 'in_progress' => 0, 'closed' => 0];
foreach ($stmt_us->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $key = $row['status'] === 'in-progress' ? 'in_progress' : $row['status'];
    if (array_key_exists($key, $stats)) {
        $stats[$key] = (int)$row['cnt'];
    }
    $stats['total'] += (int)$row['cnt'];
}
$u_total        = max($stats['total'], 1);
$pct_open_u     = (int)round($stats['open']        / $u_total * 100);
$pct_progress_u = (int)round($stats['in_progress'] / $u_total * 100);
$pct_closed_u   = (int)round($stats['closed']      / $u_total * 100);

/* Compute stats */
$count_open     = count(array_filter($tickets, fn($t) => $t['status'] === 'open'));
$count_progress = count(array_filter($tickets, fn($t) => $t['status'] === 'in-progress'));
$count_high     = count(array_filter($tickets, fn($t) => $t['priority'] === 'high' && $t['status'] !== 'closed'));
$count_closed   = count(array_filter($tickets, fn($t) => $t['status'] === 'closed'));
$count_all      = count($tickets);
$count_my       = count(array_filter($tickets, fn($t) => (int)$t['user_id'] === (int)$_SESSION['user_id']));
$count_stale    = count(array_filter($tickets, function ($t) {
    if ($t['status'] === 'closed') return false;
    return (int)(new DateTime())->diff(new DateTime($t['created_at']))->days >= 3;
}));

/* Progress bar percentages (0–100, safe when count_all = 0) */
$total_pct    = max($count_all, 1);
$pct_open     = (int)round($count_open     / $total_pct * 100);
$pct_progress = (int)round($count_progress / $total_pct * 100);
$pct_high     = (int)round($count_high     / $total_pct * 100);
$pct_closed   = (int)round($count_closed   / $total_pct * 100);

$flash = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_success']);

/* Format a timestamp as a relative "time ago" string */
function time_ago(string $datetime): string {
    $diff = (new DateTime())->diff(new DateTime($datetime));
    if ($diff->days > 6)  return (new DateTime($datetime))->format('M j');
    if ($diff->days > 0)  return $diff->days  . 'd ago';
    if ($diff->h   > 0)   return $diff->h     . 'h ago';
    if ($diff->i   > 0)   return $diff->i     . 'm ago';
    return 'just now';
}

/* Format numeric ticket ID as TH-001 */
function ticket_id(int $id): string {
    return 'TH-' . str_pad($id, 3, '0', STR_PAD_LEFT);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — TicketSystem</title>
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

    <a href="/dashboard/user.php" class="sidebar-link active">
      <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
      Tickets
      <span class="sidebar-count"><?php echo $count_all; ?></span>
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
        <h1 class="page-title">Welcome back, <?php echo htmlspecialchars($display_name); ?>.</h1>
        <p class="page-sub">Here&rsquo;s what&rsquo;s happening with your tickets.</p>
      </div>
      <a href="/tickets/create.php" class="btn btn-primary">New ticket</a>
    </div>

    <div class="stats">
      <div class="stat-card stat-card--total">
        <div class="stat-number"><?php echo (int)$stats['total']; ?></div>
        <div class="stat-label">Total</div>
      </div>
      <div class="stat-card stat-card--open">
        <div class="stat-number status-open-num"><?php echo (int)$stats['open']; ?></div>
        <div class="stat-label">Open</div>
      </div>
      <div class="stat-card stat-card--progress">
        <div class="stat-number status-progress-num"><?php echo (int)$stats['in_progress']; ?></div>
        <div class="stat-label">In Progress</div>
      </div>
      <div class="stat-card stat-card--closed">
        <div class="stat-number"><?php echo (int)$stats['closed']; ?></div>
        <div class="stat-label">Closed</div>
      </div>
    </div>
    <div class="stats-proportion">
      <div class="stats-proportion-bar">
        <?php if ($stats['total'] === 0): ?>
        <span class="stats-proportion-seg stats-proportion-seg--empty"></span>
        <?php else: ?>
        <?php if ($stats['open'] > 0): ?>
        <span class="stats-proportion-seg stats-proportion-seg--open" style="width:<?php echo $pct_open_u; ?>%"></span>
        <?php endif; ?>
        <?php if ($stats['in_progress'] > 0): ?>
        <span class="stats-proportion-seg stats-proportion-seg--progress" style="width:<?php echo $pct_progress_u; ?>%"></span>
        <?php endif; ?>
        <?php if ($stats['closed'] > 0): ?>
        <span class="stats-proportion-seg stats-proportion-seg--closed" style="width:<?php echo $pct_closed_u; ?>%"></span>
        <?php endif; ?>
        <?php endif; ?>
      </div>
      <div class="stats-proportion-legend">
        <span class="stats-proportion-legend-item stats-proportion-legend-item--open">Open &mdash; <?php echo $pct_open_u; ?>%</span>
        <span class="stats-proportion-legend-item stats-proportion-legend-item--progress">In Progress &mdash; <?php echo $pct_progress_u; ?>%</span>
        <span class="stats-proportion-legend-item stats-proportion-legend-item--closed">Closed &mdash; <?php echo $pct_closed_u; ?>%</span>
      </div>
    </div>

    <!-- Quick-filter chips -->
    <div class="filter-chips">
      <button class="chip active" data-chip="all">All <span class="chip-count"><?php echo (int)$count_all; ?></span></button>
      <button class="chip" data-chip="mine">My tickets <span class="chip-count"><?php echo (int)$count_my; ?></span></button>
      <button class="chip" data-chip="high">High priority <span class="chip-count"><?php echo (int)$count_high; ?></span></button>
      <button class="chip" data-chip="open">Unresolved <span class="chip-count"><?php echo (int)($count_open + $count_progress); ?></span></button>
      <?php if ($count_stale > 0): ?>
      <button class="chip" data-chip="stale">Stale 3+ days <span class="chip-count"><?php echo (int)$count_stale; ?></span></button>
      <?php endif; ?>
    </div>

    <!-- Toolbar -->
    <div class="tickets-toolbar">
      <input class="input" id="search" type="search" placeholder="Search tickets&hellip;" style="max-width:260px">
      <select class="input" id="filter-status" style="max-width:160px">
        <option value="">All statuses</option>
        <option value="open">Open</option>
        <option value="in-progress">In Progress</option>
        <option value="closed">Closed</option>
      </select>
      <select class="input" id="filter-priority" style="max-width:160px">
        <option value="">All priorities</option>
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
      </select>
    </div>

    <!-- Ticket table -->
    <table class="tickets-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Status</th>
          <th>Priority</th>
          <th>Author</th>
          <th>Age</th>
          <th></th>
        </tr>
      </thead>
      <tbody>

        <?php if (empty($tickets)): ?>
        <tr>
          <td colspan="7">
            <div class="empty-state">
              <div class="empty-state-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
              </div>
              <div class="empty-state-title">No tickets yet</div>
              <p class="empty-state-body">Create your first ticket to get started.</p>
              <a href="/tickets/create.php" class="btn btn-primary">New ticket</a>
            </div>
          </td>
        </tr>

        <?php else: ?>
        <?php foreach ($tickets as $ticket):
          $tid      = ticket_id((int)$ticket['id']);
          $ago      = time_ago($ticket['created_at']);
          $status   = $ticket['status'];
          $priority = $ticket['priority'];
          $is_owner = (int)$ticket['user_id'] === (int)$_SESSION['user_id'];

          $days_old  = (int)(new DateTime())->diff(new DateTime($ticket['created_at']))->days;
          $is_stale  = $status !== 'closed' && $days_old >= 3;
          $age_class = '';
          if ($status !== 'closed') {
              if ($days_old >= 3) $age_class = 'age-stale';
              elseif ($days_old >= 1) $age_class = 'age-aging';
          }

          $status_label = match($status) {
              'open'        => 'Open',
              'in-progress' => 'In Progress',
              'closed'      => 'Closed',
              default       => ucfirst($status),
          };
          $priority_label = ucfirst($priority);
        ?>
        <tr class="ticket-row"
            data-title="<?php echo htmlspecialchars(strtolower($ticket['title'])); ?>"
            data-status="<?php echo htmlspecialchars($status); ?>"
            data-priority="<?php echo htmlspecialchars($priority); ?>"
            data-owner="<?php echo $is_owner ? '1' : '0'; ?>"
            data-stale="<?php echo $is_stale ? '1' : '0'; ?>">
          <td class="ticket-id"><?php echo $tid; ?></td>
          <td class="ticket-title"><a href="/tickets/view.php?id=<?php echo (int)$ticket['id']; ?>" class="ticket-title-link"><?php echo htmlspecialchars($ticket['title']); ?></a></td>
          <td>
            <span class="pill status-<?php echo htmlspecialchars($status === 'in-progress' ? 'progress' : $status); ?>">
              <span class="pill-dot"></span>
              <?php echo htmlspecialchars($status_label); ?>
            </span>
          </td>
          <td>
            <span class="pill priority-<?php echo htmlspecialchars($priority); ?>">
              <span class="pill-dot"></span>
              <?php echo htmlspecialchars($priority_label); ?>
            </span>
          </td>
          <td><?php echo htmlspecialchars($ticket['author']); ?></td>
          <td class="<?php echo $age_class; ?>"><?php echo htmlspecialchars($ago); ?></td>
          <td>
            <div class="ticket-actions">
              <?php if ($is_owner || is_admin()): ?>
              <a href="/tickets/edit.php?id=<?php echo (int)$ticket['id']; ?>" class="btn btn-ghost btn-sm">Edit</a>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>

        <tr class="ticket-empty" style="display:none">
          <td colspan="7">
            <div class="empty-state">
              <div class="empty-state-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="8" x2="14" y2="14"/><line x1="14" y1="8" x2="8" y2="14"/></svg>
              </div>
              <div class="empty-state-title">No tickets found</div>
              <p class="empty-state-body">Try adjusting your search or filters.</p>
              <button class="btn btn-ghost btn-sm" id="clear-filters">Clear filters</button>
            </div>
          </td>
        </tr>
        <?php endif; ?>

      </tbody>
    </table>

  </main>
</div>

<script src="/public/js/toast.js"></script>
<?php if ($flash): ?>
<script>showToast(<?php echo json_encode($flash); ?>, 'success');</script>
<?php endif; ?>

<script>
  (function () {
    var search    = document.getElementById('search');
    var fStatus   = document.getElementById('filter-status');
    var fPriority = document.getElementById('filter-priority');
    var rows      = document.querySelectorAll('.ticket-row');
    var empty     = document.querySelector('.ticket-empty');
    var activeChip = 'all';

    function applyFilters() {
      var q  = search.value.toLowerCase();
      var st = fStatus.value;
      var pr = fPriority.value;
      var visible = 0;

      rows.forEach(function (row) {
        var show = (!q  || row.dataset.title.includes(q))
                && (!st || row.dataset.status === st)
                && (!pr || row.dataset.priority === pr);

        if (activeChip === 'mine')  show = show && row.dataset.owner === '1';
        if (activeChip === 'high')  show = show && row.dataset.priority === 'high';
        if (activeChip === 'open')  show = show && (row.dataset.status === 'open' || row.dataset.status === 'in-progress');
        if (activeChip === 'stale') show = show && row.dataset.stale === '1';

        row.style.display = show ? '' : 'none';
        if (show) visible++;
      });

      if (empty) empty.style.display = visible === 0 ? '' : 'none';
    }

    search.addEventListener('input', applyFilters);
    fStatus.addEventListener('change', applyFilters);
    fPriority.addEventListener('change', applyFilters);

    document.querySelectorAll('.chip').forEach(function (chip) {
      chip.addEventListener('click', function () {
        document.querySelectorAll('.chip').forEach(function (c) { c.classList.remove('active'); });
        chip.classList.add('active');
        activeChip = chip.dataset.chip;
        fStatus.value = '';
        fPriority.value = '';
        applyFilters();
      });
    });

    var clearBtn = document.getElementById('clear-filters');
    if (clearBtn) {
      clearBtn.addEventListener('click', function () {
        search.value = '';
        fStatus.value = '';
        fPriority.value = '';
        activeChip = 'all';
        document.querySelectorAll('.chip').forEach(function (c) { c.classList.remove('active'); });
        var allChip = document.querySelector('.chip[data-chip="all"]');
        if (allChip) allChip.classList.add('active');
        applyFilters();
      });
    }
  }());
</script>

<?php if (is_admin()): ?>
<script src="/chat/chat.js"></script>
<?php endif; ?>
<script src="/public/js/canvas.js"></script>
</body>
</html>
