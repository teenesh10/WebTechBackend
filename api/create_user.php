<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json');

require_once '../config.php';

// Connect to the database
$db = new db();
$conn = $db->connect();

// Sample user data
$users = [
    [
        'email' => 'admin@example.com',
        'username' => 'admin',
        'password' => 'admin123',  // Plain text password
        'roleID' => 1,             // Assuming 1 is for admin
        'resetToken' => ''
    ],
    [
        'email' => 'tester@example.com',
        'username' => 'tester',
        'password' => 'tester123', // Plain text password
        'roleID' => 2,             // Assuming 2 is for tester
        'resetToken' => ''
    ]
];

foreach ($users as $user) {
    // Hash the password
    $hashedPassword = password_hash($user['password'], PASSWORD_BCRYPT);
    
    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO Users (email, username, password, roleID, resetToken) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$user['email'], $user['username'], $hashedPassword, $user['roleID'], $user['resetToken']]);
}

echo json_encode(["message" => "Test users added successfully"]);
