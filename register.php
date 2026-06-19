<?php
require_once 'db.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $name    = trim($_POST['User_name']);
    $email   = trim($_POST['User_email']);
    $password= $_POST['User_password'];
    $phone   = trim($_POST['User_phone']);
    $address = trim($_POST['User_address']);

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        die("Please fill in all required fields (Name, Email, Password).");
    }

    try {
        // 1. Check if the email already exists in the database
        $checkEmail = $conn->prepare("SELECT User_ID FROM User WHERE User_email = :email");
        $checkEmail->execute([':email' => $email]);
        
        if ($checkEmail->rowCount() > 0) {
            die("Error: This email is already registered.");
        }

        // 2. Hash the password securely (Never store plain text!)
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // 3. Insert the data using Prepared Statements (Prevents SQL Injection)
        $sql = "INSERT INTO User (User_name, User_email, User_password, User_phone, User_address) 
                VALUES (:name, :email, :password, :phone, :address)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':name'     => $name,
            ':email'    => $email,
            ':password' => $hashedPassword, // Saving the secure hash
            ':phone'    => $phone,
            ':address'  => $address
        ]);

        echo "Registration successful! You can now log in.";

    } catch (PDOException $e) {
        echo "Registration failed: " . $e->getMessage();
    }
}
?>