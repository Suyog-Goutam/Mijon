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
