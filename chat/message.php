<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

if (!is_admin()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$body    = file_get_contents('php://input');
$data    = json_decode($body, true);

if ($data === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON.']);
    exit();
}

$message = trim($data['message'] ?? '');

if ($message === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required.']);
    exit();
}

if (strlen($message) > 1000) {
    http_response_code(400);
    echo json_encode(['error' => 'Message too long.']);
    exit();
}

/* ── Detect ticket ID in message ── */
$ticket_context = '';
if (preg_match('/\bTH-(\d+)\b/i', $message, $matches)) {
    $ticket_id = (int)$matches[1];
    $stmt = $pdo->prepare(
        'SELECT t.id, t.title, t.description, t.status, t.priority, u.username AS author
         FROM tickets t JOIN users u ON t.user_id = u.id
         WHERE t.id = :id LIMIT 1'
    );
    $stmt->execute([':id' => $ticket_id]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($ticket) {
        $tid = 'TH-' . str_pad((int)$ticket['id'], 3, '0', STR_PAD_LEFT);
        $ticket_context = "\n\nThe user is asking about the following ticket:\n"
            . "ID: {$tid}\n"
            . "Title: {$ticket['title']}\n"
            . "Description: {$ticket['description']}\n"
            . "Status: {$ticket['status']} | Priority: {$ticket['priority']} | Author: {$ticket['author']}\n"
            . "\nAnalyse this issue and suggest practical, step-by-step resolution strategies.";
    }
}

/* ── Build system prompt ── */
$system_prompt = 'You are a helpful IT support assistant for TicketHub. Answer questions clearly and concisely.'
    . $ticket_context;

$payload = json_encode([
    'model'    => 'llama3.1:8b',
    'messages' => [
        ['role' => 'system', 'content' => $system_prompt],
        ['role' => 'user',   'content' => $message],
    ],
    'stream' => false,
]);

/* ── Call Ollama ── */
$ctx = stream_context_create([
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json\r\n",
        'content' => $payload,
        'timeout' => 30,
    ],
]);

$response = @file_get_contents('http://localhost:11434/api/chat', false, $ctx);

if ($response === false) {
    echo json_encode(['error' => 'Could not reach AI. Is Ollama running?']);
    exit();
}

$result = json_decode($response, true);
$reply  = $result['message']['content'] ?? 'No response from AI.';

echo json_encode(['reply' => $reply]);
