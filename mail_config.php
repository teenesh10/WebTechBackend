<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include Composer's autoloader
require 'vendor/autoload.php';

function sendResetEmail($userEmail, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.your-email-provider.com'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                          // Enable SMTP authentication
        $mail->Username   = 'your-email@example.com';      // SMTP username
        $mail->Password   = 'your-email-password';         // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 587;                           // TCP port to connect to

        // Recipients
        $mail->setFrom('no-reply@yourdomain.com', 'Your App Name');
        $mail->addAddress($userEmail);                      // Add a recipient

        // Content
        $mail->isHTML(true);                               // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        // Handle the error as needed
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}
?>
