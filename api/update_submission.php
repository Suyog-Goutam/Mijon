<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');

// Set timezone
date_default_timezone_set('Asia/Kathmandu');

// Read POST body
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['lineIndex']) || !isset($input['status'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields: lineIndex, status']);
    exit();
}

$lineIndex = (int) $input['lineIndex'];
$status = $input['status']; // 'approved', 'denied', 'edited'
$revisedDate = isset($input['revised_date']) ? trim($input['revised_date']) : '';
$revisedTime = isset($input['revised_time']) ? trim($input['revised_time']) : '';

$contactFile = '../contact.txt';

if (!file_exists($contactFile)) {
    http_response_code(404);
    echo json_encode(['error' => 'contact.txt not found']);
    exit();
}

$lines = file($contactFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if ($lineIndex < 0 || $lineIndex >= count($lines)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid line index']);
    exit();
}

// Decode the target line
$data = json_decode($lines[$lineIndex], true);
if (!$data) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to parse submission data']);
    exit();
}

// Update the status
$data['status'] = $status;
$data['status_updated_at'] = date('Y-m-d H:i:s');

// If edited, store revised date/time
if ($status === 'edited' && ($revisedDate || $revisedTime)) {
    if ($revisedDate) $data['revised_date'] = $revisedDate;
    if ($revisedTime) $data['revised_time'] = $revisedTime;
}

// Write back the updated line
$lines[$lineIndex] = json_encode($data);

// Rebuild the file
$fileContent = implode(PHP_EOL, $lines) . PHP_EOL;
file_put_contents($contactFile, $fileContent, LOCK_EX);

echo json_encode(['success' => true, 'updated' => $data]);
?>
