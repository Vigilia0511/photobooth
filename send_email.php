<?php
// send_email.php

// Include PHPMailer (download from https://github.com/PHPMailer/PHPMailer and place in the same directory or adjust path)
require __DIR__ . '/PHPMailer-7.0.1/src/Exception.php';
require __DIR__ . '/PHPMailer-7.0.1/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-7.0.1/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'joshuacajimatvigilia@gmail.com'; // Your Gmail
        $mail->Password = 'amzbpgiasamsnghe'; // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('joshuacajimatvigilia@gmail.com', 'Photobooth');
        $mail->addAddress('joshuacajimatvigilia@gmail.com', 'Recipient'); // Send to yourself or change to user input

        // Attachments
        $mail->addAttachment($_FILES['image']['tmp_name'], 'photobooth_strip.png');

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