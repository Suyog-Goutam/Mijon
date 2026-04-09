<?php
require_once __DIR__ . '/../config/db_config.php';

/**
 * Get a secure PDO connection to the MySQL database.
 * Uses Prepared Statements to prevent SQL Injection.
 */
function get_db_connection() {
    if (STORAGE_MODE !== 'mysql') {
        return null;
    }

    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // Log error and return null (in production, don't show specific connection errors)
        error_log("Database Connection Error: " . $e->getMessage());
        return null;
    }
}
?>
