<?php
header('Content-Type: application/json');

// Define path to the JSON file
$file_path = __DIR__ . '/../blogs.json';

// Return empty array if file doesn't exist yet
if (!file_exists($file_path)) {
    echo json_encode([]);
    exit;
}

// Read and return the JSON file contents
$json_data = file_get_contents($file_path);
echo $json_data;
?>
