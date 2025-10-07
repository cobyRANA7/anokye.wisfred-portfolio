<?php
require 'config';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Gmail configuration
$mail = new PHPMailer(true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $projectType = htmlspecialchars($_POST['projectType']);
    $budget = htmlspecialchars($_POST['budget']);
    $message = htmlspecialchars($_POST['message']);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'anokye.wisfred22@gmail.com';
        $mail->Password = 'pmui urrx kbfh pnji'; // App Password from Google
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender & recipient
        $mail->setFrom('anokye.wisfred22@gmail.com', 'Wisfred\'s Building Consult');
        $mail->addAddress('anokye.wisfred22@gmail.com', 'Wisfred');

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New Project Quote from ' . $firstName . ' ' . $lastName;
        $mail->Body = "
            <h2>New Quote Request</h2>
            <p><strong>Name:</strong> $firstName $lastName</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Phone:</strong> $phone</p>
            <p><strong>Project Type:</strong> $projectType</p>
            <p><strong>Budget:</strong> $budget</p>
            <p><strong>Message:</strong><br>$message</p>
        ";

        $mail->send();

        echo "<script>alert('✅ Your quote has been sent successfully!'); window.location.href='contact.html';</script>";
    } catch (Exception $e) {
        echo "<script>alert('❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}'); window.history.back();</script>";
    }
}
?>


