<?php
header('Content-Type: application/json');

// Get the JSON data sent from the frontend
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data provided.']);
    exit;
}

try {
    // Connect to cams.db (SQLite)
    $db = new PDO('sqlite:cams.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create user table if it doesn't exist
    $db->exec("CREATE TABLE IF NOT EXISTS user (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        status TEXT DEFAULT 'active',
        role TEXT DEFAULT 'user'
    )");

    // Check if user or email already exists
    $checkStmt = $db->prepare("SELECT id FROM user WHERE username = :u OR email = :e");
    $checkStmt->execute([':u' => $data['username'], ':e' => $data['email']]);
    
    if ($checkStmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Username or Email already exists.']);
        exit;
    }

    // Hash the password for security
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    // Insert new user
    $insertStmt = $db->prepare("INSERT INTO user (username, email, password) VALUES (:u, :e, :p)");
    $insertStmt->execute([':u' => $data['username'], ':e' => $data['email'], ':p' => $hashedPassword]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>