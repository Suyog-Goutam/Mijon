<?php
// api/delete_media.php
header('Content-Type: application/json');

session_start();
// Security Check: Only allow logged-in admins to delete files
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$filePath = $input['path'] ?? '';

if (empty($filePath)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No file path provided']);
    exit;
}

// Security: Prevent path traversal (e.g. ../../../etc/passwd)
// Ensure the path starts with 'images/' or 'videos/' and doesn't contain '..'
if (!preg_match('/^(images\/|videos\/)/', $filePath) || strpos($filePath, '..') !== false) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Access denied. You can only delete files from the images/ or videos/ folders.']);
    exit;
}

$fullPath = __DIR__ . '/../' . $filePath;

if (file_exists($fullPath)) {
    if (unlink($fullPath)) {
        echo json_encode(['success' => true, 'message' => 'File deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Failed to delete file from disk. Check permissions.']);
    }
} else {
    // File might have already been deleted or path is wrong, but we return success to allow the UI to proceed
    echo json_encode(['success' => true, 'message' => 'File not found on server, assuming already removed.']);
}
?>
