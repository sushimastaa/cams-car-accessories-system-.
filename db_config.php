<?php
/**
 * Database Configuration
 * This file is deprecated - use config.php instead for centralized configuration
 */

// Include the main configuration file
require_once dirname(__FILE__) . '/config.php';

// Create database connection using the centralized configuration
$conn = connectDB();
?>