<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json');

require_once '../config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['username']) && isset($data['password'])) {
    $db = new db();
    $conn = $db->connect();
    
    $username = $data['username'];
    $password = $data['password'];
    
    // Fetch user by username
    $stmt = $conn->prepare("SELECT * FROM Users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        // Successfully authenticated
        session_start();
        $_SESSION['userID'] = $user['userID'];
        $_SESSION['roleID'] = $user['roleID'];
        echo json_encode([
            "message" => "Login successful",
            "user" => $user
        ]);
    } else {
        // Invalid credentials
        http_response_code(401);
        echo json_encode(["error" => "Invalid username or password"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Username and password are required"]);
}
