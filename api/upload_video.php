<?php
// api/upload_video.php
header('Content-Type: application/json');

session_start();
// Security check: Only allow logged-in admins to upload
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Unauthorized. Please login as admin.']);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method Not Allowed']);
    exit;
}

// Check if file was uploaded without errors
if (!isset($_FILES['video']) || $_FILES['video']['error'] !== UPLOAD_ERR_OK) {
    $errorMsg = 'No file uploaded or upload error occurred.';
    if (isset($_FILES['video'])) {
        switch ($_FILES['video']['error']) {
            case UPLOAD_ERR_INI_SIZE: $errorMsg = 'File exceeds upload_max_filesize in php.ini'; break;
            case UPLOAD_ERR_FORM_SIZE: $errorMsg = 'File exceeds MAX_FILE_SIZE in form'; break;
            case UPLOAD_ERR_PARTIAL: $errorMsg = 'File was only partially uploaded'; break;
            case UPLOAD_ERR_NO_FILE: $errorMsg = 'No file was uploaded'; break;
            case UPLOAD_ERR_NO_TMP_DIR: $errorMsg = 'Missing temporary folder'; break;
            case UPLOAD_ERR_CANT_WRITE: $errorMsg = 'Failed to write file to disk'; break;
        }
    }
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $errorMsg]);
    exit;
}

$file = $_FILES['video'];
$fileName = $file['name'];
$fileTmpPath = $file['tmp_name'];
$fileSize = $file['size'];

// Max size: 200MB (This is just a double-check; server limits usually apply first)
if ($fileSize > 200 * 1024 * 1024) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'File size exceeds the 200MB limit.']);
    exit;
}

// Validate file extension
$allowedExtensions = ['mp4', 'webm', 'ogg', 'mov'];
$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

if (!in_array($fileExtension, $allowedExtensions)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid file type. Only MP4, WEBM, OGG, and MOV are allowed.']);
    exit;
}

// Generate a unique filename to prevent overwriting
$newFileName = uniqid('vid_', true) . '.' . $fileExtension;

// The destination path (../videos/filename)
$uploadDir = __DIR__ . '/../videos/';
$destFilePath = $uploadDir . $newFileName;

// Ensure videos directory exists
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Move the file
if (move_uploaded_file($fileTmpPath, $destFilePath)) {
    // Return relative path from the root perspective (e.g., videos/vid_123.mp4)
    echo json_encode(['success' => true, 'path' => 'videos/' . $newFileName]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file. Check folder permissions.']);
}
?>
