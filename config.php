<?php
/**
 * Car Accessories Management System
 * Global Configuration File
 * 
 * This file contains configuration settings for local development
 * with XAMPP Live Server
 */

// ========== SERVER CONFIGURATION ==========
define('SITE_URL', 'http://localhost/cams/');
define('SITE_ROOT', dirname(__FILE__));
define('UPLOAD_DIR', SITE_ROOT . '/uploads/');

// ========== DATABASE CONFIGURATION ==========
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cams_db');
define('DB_PORT', 3306);

// ========== API ENDPOINTS ==========
define('API_BASE_URL', SITE_URL . 'api_');

// ========== ERROR HANDLING ==========
// Set to true for development (shows errors), false for production
define('DEBUG_MODE', true);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// ========== DATABASE CONNECTION FUNCTION ==========
function connectDB() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    
    if ($conn->connect_error) {
        if (DEBUG_MODE) {
            die("Database Connection Error: " . $conn->connect_error);
        } else {
            die("Database connection failed. Please try again later.");
        }
    }
    
    // Set character set to utf8
    $conn->set_charset("utf8");
    
    return $conn;
}

// ========== JSON RESPONSE HELPER ==========
function sendJSON($data, $statusCode = 200) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

// ========== CORS HEADERS ==========
header('Access-Control-Allow-Origin: ' . SITE_URL);
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

?>
