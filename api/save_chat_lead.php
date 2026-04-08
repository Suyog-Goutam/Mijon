<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON data']);
    exit();
}

$name    = trim($input['name'] ?? '');
$phone   = trim($input['phone'] ?? '');
$email   = trim($input['email'] ?? '');
$service = trim($input['service'] ?? 'General Consultation');
$date    = trim($input['date'] ?? '');
$time    = trim($input['time'] ?? 'Morning');
$message = trim($input['message'] ?? 'Appointment requested via Chatbot');

if (empty($name) || empty($phone)) {
    http_response_code(400);
    echo json_encode(['error' => 'Name and phone number are required']);
    exit();
}

// Split full name into first/last
$nameParts = explode(' ', $name, 2);
$firstName = $nameParts[0];
$lastName  = isset($nameParts[1]) ? $nameParts[1] : '';

$entry = [
    'timestamp'  => date('Y-m-d H:i:s'),
    'type'       => 'Booking',
    'source'     => 'Chatbot',
    'first_name' => htmlspecialchars($firstName),
    'last_name'  => htmlspecialchars($lastName),
    'phone'      => htmlspecialchars($phone),
    'email'      => htmlspecialchars($email),
    'message'    => htmlspecialchars($message),
    'service'    => htmlspecialchars($service),
    'date'       => htmlspecialchars($date),
    'time'       => htmlspecialchars($time),
    'ip'         => $_SERVER['REMOTE_ADDR'] ?? ''
];

$contactFile = '../contact.txt';
$line = json_encode($entry) . "\n";

if (file_put_contents($contactFile, $line, FILE_APPEND | LOCK_EX) !== false) {
    echo json_encode(['success' => true, 'message' => 'Your appointment request has been received! We will call you shortly.']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Could not save your booking. Please call us directly at 9825951131.']);
}
?>
