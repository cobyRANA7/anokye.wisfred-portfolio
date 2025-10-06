<?php
require_once __DIR__ . '/config.php';

// =============================
// CONTACT FORM MAIL HANDLER
// =============================

// Load PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/config.php'; // Your secure credentials

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize user input
    $firstName   = htmlspecialchars($_POST['firstName'] ?? '');
    $lastName    = htmlspecialchars($_POST['lastName'] ?? '');
    $email       = htmlspecialchars($_POST['email'] ?? '');
    $phone       = htmlspecialchars($_POST['phone'] ?? '');
    $projectType = htmlspecialchars($_POST['projectType'] ?? '');
    $budget      = htmlspecialchars($_POST['budget'] ?? '');
    $message     = htmlspecialchars($_POST['message'] ?? '');

    // Build email body
    $body = "
        <h2>New Quote Request from Portfolio Website</h2>
        <p><strong>First Name:</strong> {$firstName}</p>
        <p><strong>Last Name:</strong> {$lastName}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Project Type:</strong> {$projectType}</p>
        <p><strong>Budget:</strong> {$budget}</p>
        <p><strong>Message:</strong><br>{$message}</p>
    ";

    // Send email
    $mail = new PHPMailer(true);

    try {
        // Gmail SMTP Settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email headers
        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress(MAIL_TO, MAIL_TO_NAME);

        // Reply to the client
        if (!empty($email)) {
            $mail->addReplyTo($email, "{$firstName} {$lastName}");
        }

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'New Quote Request';
        $mail->Body    = $body;

        // Send message
        $mail->send();

        // Redirect to Thank You page
        header("Location: thankyou.html");
        exit();

    } catch (Exception $e) {
        echo "Sorry, your message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request.";
}
?>


<!-- ====== Contact Form HTML ====== -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request a Quote</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background: #f2f2f2;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }
        input, textarea, select, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<h2>Request a Quote</h2>

<form action="contact.php" method="POST">
    <label>First Name</label>
    <input type="text" name="firstName" required>

    <label>Last Name</label>
    <input type="text" name="lastName" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Phone</label>
    <input type="text" name="phone">

    <label>Project Type</label>
    <input type="text" name="projectType">

    <label>Budget</label>
    <input type="text" name="budget">

    <label>Message</label>
    <textarea name="message" required></textarea>

    <button type="submit">Send Quote</button>
</form>

</body>
</html>
