<?php
// send_email.php

// Include PHPMailer (download from https://github.com/PHPMailer/PHPMailer and place in the same directory or adjust path)
require __DIR__ . '/PHPMailer-7.0.1/src/Exception.php';
require __DIR__ . '/PHPMailer-7.0.1/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-7.0.1/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

// Load environment variables from .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        putenv("$name=$value");
        $_ENV[$name] = $value;
    }
}
loadEnv(__DIR__ . '/.env');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = getenv('EMAIL_USERNAME');
        $mail->Password = getenv('EMAIL_PASSWORD');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Recipients
        $mail->setFrom(getenv('EMAIL_USERNAME'), 'Photobooth');
        $mail->addAddress(getenv('EMAIL_USERNAME'), 'Recipient'); // Send to yourself or change to user input

        // Attachments
        $imageContent = file_get_contents($_FILES['image']['tmp_name']);
        $mail->addStringAttachment($imageContent, 'photobooth_strip.png', 'base64', 'image/png');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Photobooth Strip';
        $mail->Body = 'Here is your photobooth strip image.';
        $mail->AltBody = 'Here is your photobooth strip image.';

        $mail->send();
        echo json_encode(['success' => true, 'message' => 'Email sent successfully!']);
    } catch (PHPMailerException $e) {
        echo json_encode(['error' => 'Email could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>