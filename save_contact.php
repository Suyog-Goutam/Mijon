<?php
// Set local timezone for Nepal (UTC+05:45)
date_default_timezone_set('Asia/Kathmandu');

// Rate Limit Config
$RATE_LIMIT_SECONDS = 300; // 5 minutes
$RATE_LIMIT_FILE = 'rate_limit.json';

/**
 * Get the visitor's real IP address
 */
function getClientIP() {
    // Check for forwarded IPs (proxies, load balancers)
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ips[0]);
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

/**
 * Check if this IP is rate limited
 * Returns: false if allowed, or seconds remaining if blocked
 */
function isRateLimited($ip, $limitSeconds, $filePath) {
    $records = [];
    
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $records = json_decode($content, true);
        if (!is_array($records)) $records = [];
    }
    
    // Clean up expired entries (older than limit)
    $now = time();
    foreach ($records as $key => $timestamp) {
        if (($now - $timestamp) > $limitSeconds) {
            unset($records[$key]);
        }
    }
    
    // Save cleaned records
    file_put_contents($filePath, json_encode($records), LOCK_EX);
    
    // Check if this IP has a recent submission
    if (isset($records[$ip])) {
        $elapsed = $now - $records[$ip];
        $remaining = $limitSeconds - $elapsed;
        if ($remaining > 0) {
            return $remaining; // Still rate limited
        }
    }
    
    return false; // Not limited
}

/**
 * Record this IP's submission timestamp
 */
function recordSubmission($ip, $filePath) {
    $records = [];
    
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $records = json_decode($content, true);
        if (!is_array($records)) $records = [];
    }
    
    $records[$ip] = time();
    file_put_contents($filePath, json_encode($records), LOCK_EX);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $clientIP = getClientIP();
    $returnUrl = isset($_POST['return_url']) ? $_POST['return_url'] : 'index.html';
    
    // Check rate limit
    $blocked = isRateLimited($clientIP, $RATE_LIMIT_SECONDS, $RATE_LIMIT_FILE);
    
    if ($blocked !== false) {
        $minutesLeft = ceil($blocked / 60);
        // Redirect back with error
        header("Location: " . $returnUrl . "?error=ratelimit&wait=" . $minutesLeft);
        exit();
    }
    
    // Collect data
    $firstName = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $lastName = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $type = isset($_POST['type']) ? trim($_POST['type']) : 'Contact';
    $service = isset($_POST['service']) ? trim($_POST['service']) : '';
    $date = isset($_POST['date']) ? trim($_POST['date']) : '';
    $time = isset($_POST['time']) ? trim($_POST['time']) : '';

    $entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'type' => $type,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'phone' => $phone,
        'email' => $email,
        'message' => $message,
        'service' => $service,
        'date' => $date,
        'time' => $time,
        'ip' => $clientIP
    ];

    $jsonEntry = json_encode($entry) . PHP_EOL;

    // Save to contact.txt
    file_put_contents('contact.txt', $jsonEntry, FILE_APPEND | LOCK_EX);
    
    // Record this submission for rate limiting
    recordSubmission($clientIP, $RATE_LIMIT_FILE);

    // Redirect to a success page or back with success parameter
    header("Location: " . $returnUrl . "?success=1");
    exit();
}
?>
