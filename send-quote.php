<?php
require 'config.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require DIR . '/PHPMailer/src/Exception.php';
require DIR . '/PHPMailer/src/PHPMailer.php';
require DIR . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/config.php';

function clean($value) {
  return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

// Collect form data
$firstName   = clean($_POST['firstName'] ?? '');
$lastName    = clean($_POST['lastName'] ?? '');
$email       = clean($_POST['email'] ?? '');
$phone       = clean($_POST['phone'] ?? '');
$projectType = clean($_POST['projectType'] ?? '');
$budget      = clean($_POST['budget'] ?? '');
$message     = clean($_POST['message'] ?? '');

if (!$firstName || !$lastName || !$email || !$message) {
  http_response_code(400);
  echo "Please fill all required fields.";
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo "Invalid email address.";
  exit;
}

// Brand details
$COMPANY_NAME = "Wisfred’s Building Consult";
$COMPANY_EMAIL = "anokye.wisfred22@gmail.com";
$COMPANY_PHONE = "+233 (0) 548 749 728";
$COMPANY_WEBSITE = "https://cobyrana7.github.io/anokye.wisfred-portfolio/"; // replace with your actual website
$PRIMARY_COLOR = "#0056b3";

$mail = new PHPMailer(true);

try {
  // ========== Send to Admin ==========
  $mail->isSMTP();
  $mail->Host       = SMTP_HOST;
  $mail->SMTPAuth   = true;
  $mail->Username   = 'anokye.wisfred22@gmail.com';
  $mail->Password   = 'pmui urrx kbfh pnji';
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port       = SMTP_PORT;

  $mail->setFrom(FROM_EMAIL, FROM_NAME);
  $mail->addAddress('anokye.wisfred22@gmail.com');
  $mail->addReplyTo($email, "$firstName $lastName");

  $mail->isHTML(true);
  $mail->Subject = "New Quote Request from {$firstName} {$lastName}";
  $mail->Body = "
    <h2 style='color:{$PRIMARY_COLOR};'>New Quote / Message Received</h2>
    <p><strong>Name:</strong> {$firstName} {$lastName}</p>
    <p><strong>Email:</strong> {$email}</p>
    <p><strong>Phone:</strong> {$phone}</p>
    <p><strong>Project Type:</strong> {$projectType}</p>
    <p><strong>Budget:</strong> {$budget}</p>
    <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
    <hr>
    <p>Sent on: " . date('Y-m-d H:i:s') . "</p>
  ";
  $mail->send();

  // ========== Auto-reply to Client ==========
  $reply = new PHPMailer(true);
  $reply->isSMTP();
  $reply->Host       = SMTP_HOST;
  $reply->SMTPAuth   = true;
  $reply->Username   = SMTP_USER;
  $reply->Password   = SMTP_PASS;
  $reply->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $reply->Port       = SMTP_PORT;

  $reply->setFrom(FROM_EMAIL, Wisfred’s Building Consult);
  $reply->addAddress($email, "$firstName $lastName");
  $reply->isHTML(true);
  $reply->Subject = "Thank You for Contacting Wisfred's Building Consultancy";

  $reply->Body = "
  <div style='font-family: Poppins, Arial, sans-serif; max-width: 600px; margin: auto; border: 1px solid #eaeaea; border-radius: 10px; overflow: hidden;'>
    <div style='background-color: {$PRIMARY_COLOR}; color: white; text-align: center; padding: 30px;'>
      " . ($LOGO_URL ? "<img src='{$LOGO_URL}' alt='Wisfred Logo' style='max-height:60px; margin-bottom:10px;'>" : "") . "
      <h2 style='margin:0;'>Wisfred's Building Consult</h2>
    </div>

    <div style='padding: 25px; color: #333; background: #fff;'>
      <p>Dear <strong>{$firstName}</strong>,</p>
      <p>Thank you for reaching out to <strong>Wisfred's Building Consult</strong>. We’ve received your request and our team will contact you shortly to discuss your project.</p>
      
      <h3 style='color: {$PRIMARY_COLOR}; margin-top: 25px;'>Your Message Summary</h3>
      <table style='width: 100%; border-collapse: collapse;'>
        <tr><td style='padding:6px 0;'><strong>Project Type:</strong></td><td>{$projectType}</td></tr>
        <tr><td style='padding:6px 0;'><strong>Budget:</strong></td><td>{$budget}</td></tr>
        <tr><td style='padding:6px 0;'><strong>Phone:</strong></td><td>{$phone}</td></tr>
        <tr><td style='padding:6px 0; vertical-align: top;'><strong>Message:</strong></td><td>" . nl2br($message) . "</td></tr>
      </table>

      <p style='margin-top:25px;'>If you have any further details to share, feel free to reply to this email.</p>
      <p style='margin-top:15px;'>Warm regards,<br>
      <strong>Wisfrd's Building Consult</strong><br>
      📞 $COMPANY_PHONE<br>
      ✉️ <a href='mailto:anokye.wisfred22@gmail.com' style='color:{$PRIMARY_COLOR};'>anokye.wisfred22@gmail.com</a><br>
      🌐 <a href='https://cobyrana7.github.io/anokye.wisfred-portfolio/' style='color:{$PRIMARY_COLOR};'>https://cobyrana7.github.io/anokye.wisfred-portfolio/</a></p>
    </div>

    <div style='background-color:#f5f5f5; text-align:center; padding:15px; font-size:13px; color:#666;'>
      &copy; " . date('Y') . "Wisfred's Buulding Cnsult. All rights reserved.
    </div>
  </div>
  ";

  $reply->send();

  // Redirect to Thank You page
  header("Location: thankyou.html");
  exit;

} catch (Exception $e) {
  error_log("Mailer Error: " . $mail->ErrorInfo);
  http_response_code(500);
  echo "Message could not be sent. Please try again later.";
}
