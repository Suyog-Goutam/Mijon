<?php
require_once __DIR__ . '/db.php';

/**
 * Migration Script: JSON to MySQL
 * This script creates the blogs table and migrates existing data from blogs.json.
 */

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("Access denied. Please login to the admin panel first.");
}

$db = get_db_connection();
if (!$db) {
    die("Error: Could not connect to database. Please check config/db_config.php and ensure STORAGE_MODE is 'mysql'.");
}

try {
    // 1. Create the table
    $sql = "CREATE TABLE IF NOT EXISTS blogs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        blog_id VARCHAR(255) UNIQUE,
        title VARCHAR(255) NOT NULL,
        date VARCHAR(100),
        tag VARCHAR(100),
        image TEXT,
        content TEXT,
        reverse BOOLEAN DEFAULT FALSE,
        media_type VARCHAR(50) DEFAULT 'image',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $db->exec($sql);
    echo "Table 'blogs' created/verified.<br>";

    // 2. Read from blogs.json
    $json_file = __DIR__ . '/../blogs.json';
    if (!file_exists($json_file)) {
        die("Success: Table created, but no blogs.json found to migrate.");
    }
    
    $blogs = json_decode(file_get_contents($json_file), true);
    if (!$blogs) {
        die("Error: blogs.json is empty or invalid.");
    }

    // 3. Insert data
    $stmt = $db->prepare("INSERT IGNORE INTO blogs (blog_id, title, date, tag, image, content, reverse, media_type) 
                          VALUES (:id, :title, :date, :tag, :image, :content, :reverse, :media_type)");

    $count = 0;
    foreach ($blogs as $blog) {
        $stmt->execute([
            ':id' => $blog['id'] ?? uniqid(),
            ':title' => $blog['title'],
            ':date' => $blog['date'],
            ':tag' => $blog['tag'],
            ':image' => $blog['image'],
            ':content' => $blog['content'],
            ':reverse' => $blog['reverse'] ? 1 : 0,
            ':media_type' => $blog['mediaType'] ?? 'image'
        ]);
        $count++;
    }

    echo "Migration successful! Migrated $count blog posts.<br>";
    echo "You can now safely switch STORAGE_MODE to 'mysql' in config/db_config.php.";

} catch (PDOException $e) {
    echo "Migration Error: " . $e->getMessage();
}
?>
