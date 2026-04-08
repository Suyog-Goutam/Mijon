<?php
session_start();

// Ensure the user is logged in before returning any data
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

header('Content-Type: application/json');

$submissions = [];
$totalBookings = 0;
$totalContacts = 0;

$contactFile = '../contact.txt';

if (file_exists($contactFile)) {
    $lines = file($contactFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $index => $line) {
        $data = json_decode($line, true);
        if ($data) {
            $data['lineIndex'] = $index; // Add line index for update API
            $submissions[] = $data;
            if (isset($data['type']) && strtolower($data['type']) === 'booking') {
                $totalBookings++;
            } else {
                $totalContacts++;
            }
        }
    }
    $submissions = array_reverse($submissions); // Newest first
}

$response = [
    'totalBookings' => $totalBookings,
    'totalContacts' => $totalContacts,
    'submissions' => $submissions
];

echo json_encode($response);
?>
