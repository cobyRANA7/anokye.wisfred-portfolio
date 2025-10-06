<?php
// =============================
// CONTACT FORM + MAIL HANDLER
// =============================
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/config.php'; // contains your Gmail credentials

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName   = htmlspecialchars($_POST['firstName'] ?? '');
    $lastName    = htmlspecialchars($_POST['lastName'] ?? '');
    $email       = htmlspecialchars($_POST['email'] ?? '');
    $phone       = htmlspecialchars($_POST['phone'] ?? '');
    $projectType = htmlspecialchars($_POST['projectType'] ?? '');
    $budget      = htmlspecialchars($_POST['budget'] ?? '');
    $message     = htmlspecialchars($_POST['message'] ?? '');

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

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
        $mail->addAddress(MAIL_TO, MAIL_TO_NAME);

        if (!empty($email)) {
            $mail->addReplyTo($email, "{$firstName} {$lastName}");
        }

        $mail->isHTML(true);
        $mail->Subject = 'New Quote Request';
        $mail->Body    = $body;

        $mail->send();

        header("Location: thankyou.html");
        exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact | Wisfred Portfolio</title>
  <link rel="stylesheet" href="styles.css">

  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f7f7f7;
      margin: 0;
      padding: 0;
    }
    .contact-section {
      max-width: 800px;
      background: #fff;
      margin: 4rem auto;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
    }
    .form-row {
      display: flex;
      gap: 1rem;
    }
    .form-group {
      flex: 1;
      margin-bottom: 1rem;
    }
    label {
      font-weight: bold;
      display: block;
      margin-bottom: 0.5rem;
    }
    input, select, textarea {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }
    button {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 0.9rem 1.5rem;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      font-size: 1rem;
      transition: background 0.3s ease;
    }
    button:hover {
      background-color: #0056b3;
    }

    /* Floating WhatsApp Button */
    .whatsapp-float {
      position: fixed;
      bottom: 25px;
      right: 25px;
      background-color: #25D366;
      color: white;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 28px;
      text-decoration: none;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      transition: all 0.3s ease;
      z-index: 999;
    }
    .whatsapp-float:hover {
      background-color: #1ebe5b;
      transform: scale(1.1);
    }
  </style>

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

  <section class="contact-section">
    <h2>Request a Quote or Send a Message</h2>

    <form action="contact.php" method="POST" class="contact-form">
      <div class="form-row">
        <div class="form-group">
          <label for="firstName">First Name *</label>
          <input type="text" id="firstName" name="firstName" required>
        </div>
        <div class="form-group">
          <label for="lastName">Last Name *</label>
          <input type="text" id="lastName" name="lastName" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="email">Email Address *</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="tel" id="phone" name="phone">
        </div>
      </div>

      <div class="form-group">
        <label for="projectType">Project Type</label>
        <select id="projectType" name="projectType">
          <option value="">Select a project type</option>
          <option value="residential">Residential</option>
          <option value="commercial">Commercial</option>
          <option value="industrial">Industrial</option>
          <option value="renovation">Renovation</option>
          <option value="consultation">Consultation</option>
          <option value="other">Other</option>
        </select>
      </div>

      <div class="form-group">
        <label for="budget">Budget Range</label>
        <select id="budget" name="budget">
          <option value="">Select a budget range</option>
          <option value="under-100k">Under $100K</option>
          <option value="100k-500k">$100K - $500K</option>
          <option value="500k-1m">$500K - $1M</option>
          <option value="1m-5m">$1M - $5M</option>
          <option value="over-5m">Over $5M</option>
        </select>
      </div>

      <div class="form-group">
        <label for="message">Project Description *</label>
        <textarea id="message" name="message" rows="5" required placeholder="Please describe your project..."></textarea>
      </div>

      <button type="submit" class="btn btn-primary btn-lg">
        Send Message <i data-lucide="send"></i>
      </button>
    </form>
  </section>

  <!-- Floating WhatsApp Button -->
  <a href="https://wa.me/233548749728?text=Hello%20Wisfred!%20I%27d%20like%20to%20discuss%20a%20project."
     class="whatsapp-float"
     target="_blank"
     title="Chat on WhatsApp">
     <i data-lucide="message-circle"></i>
  </a>

  <script>
    lucide.createIcons();
  </script>
</body>
</html>
