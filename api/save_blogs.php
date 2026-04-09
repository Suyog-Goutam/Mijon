<?php
// Set correct headers for API
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

// Get the raw POST data
$json_data = file_get_contents('php://input');

// Validate JSON
$decoded_data = json_decode($json_data, true);
if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded_data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid JSON payload']);
    exit;
}

// Security: Check if user is logged in (optional but recommended)
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Sanitize inputs to prevent SQL Injection (in future) and XSS
foreach ($decoded_data as &$blog) {
    $blog['title'] = htmlspecialchars(trim($blog['title'] ?? ''), ENT_QUOTES, 'UTF-8');
    $blog['date'] = htmlspecialchars(trim($blog['date'] ?? ''), ENT_QUOTES, 'UTF-8');
    $blog['tag'] = htmlspecialchars(trim($blog['tag'] ?? ''), ENT_QUOTES, 'UTF-8');
    
    // For content, we allow certain safe HTML tags for styling (b, i, u, p, ul, li, br, h2, h3)
    if (isset($blog['content'])) {
        $blog['content'] = strip_tags($blog['content'], '<b><i><u><p><ul><li><br><h2><h3><div>');
    }
}
unset($blog);

// Define the path to the blogs file
$file_path = __DIR__ . '/../blogs.json';

// Attempt to write the data to the file
if (file_put_contents($file_path, json_encode($decoded_data, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'message' => 'Blogs updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to write to blogs.json']);
}
?>
