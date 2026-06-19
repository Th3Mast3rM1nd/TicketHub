<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /dashboard/user.php');
    exit();
}

$ticket_id = $_POST['id'] ?? '';

if (!ctype_digit((string)$ticket_id)) {
    header('Location: /dashboard/user.php');
    exit();
}

if (is_admin()) {
    $stmt = $pdo->prepare('DELETE FROM tickets WHERE id = :id');
    $stmt->execute([':id' => $ticket_id]);
} else {
    $stmt = $pdo->prepare('DELETE FROM tickets WHERE id = :id AND user_id = :user_id');
    $stmt->execute([':id' => $ticket_id, ':user_id' => $_SESSION['user_id']]);
}

if ($stmt->rowCount() > 0) {
    $_SESSION['flash_success'] = 'Ticket deleted successfully.';
}

header('Location: /dashboard/user.php');
exit();
