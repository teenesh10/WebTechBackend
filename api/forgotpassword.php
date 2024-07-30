<?php
require 'config.php'; // Include your database connection file
require 'mail_config.php'; // Include your email configuration file (SMTP settings)

function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userEmail = $_POST['userEmail'];

    // Check if the email exists
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$userEmail]);
    $user = $stmt->fetch();

    if ($user) {
        $token = generateToken();
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Save the token and expiry to the database
        $stmt = $pdo->prepare('INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)');
        $stmt->execute([$userEmail, $token, $expiry]);

        // Send email
        $resetLink = "http://yourdomain.com/resetpassword.php?token=$token";
        $subject = 'Password Reset Request';
        $message = "You requested a password reset. Click the link below to reset your password:\n\n$resetLink";
        $headers = 'From: no-reply@yourdomain.com';

        if (mail($userEmail, $subject, $message, $headers)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send email']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Email not found']);
    }
}
?>
