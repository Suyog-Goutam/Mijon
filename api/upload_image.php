<?php
// api/upload_image.php
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

// Check if file was uploaded without errors
if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No file uploaded or upload error occurred.']);
    exit;
}

$file = $_FILES['image'];
$fileName = $file['name'];
$fileTmpPath = $file['tmp_name'];
$fileSize = $file['size'];

// Max size: 5MB
if ($fileSize > 5 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'File size exceeds the 5MB limit.']);
    exit;
}

// Validate file extension
$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if (!in_array($fileExtension, $allowedExtensions)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, WEBP, and GIF are allowed.']);
    exit;
}

// Generate a unique filename to prevent overwriting
$newFileName = uniqid('blog_', true) . '.' . $fileExtension;

// The destination path (../images/filename)
$uploadDir = __DIR__ . '/../images/';
$destFilePath = $uploadDir . $newFileName;

// Ensure images directory exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Move the file
if (move_uploaded_file($fileTmpPath, $destFilePath)) {
    // Return relative path from the root perspective (e.g., images/blog_123.jpg)
    echo json_encode(['success' => true, 'path' => 'images/' . $newFileName]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file.']);
}
?>
