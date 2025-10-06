<?php
// ===============================
// CONFIGURATION SECTION
// ===============================
require 'config.php';

// Load PHPMailer files
require DIR . '\PHPMailer\src\Exception.php';
require DIR . '\PHPMailer\src\PHPMailer.php';
require DIR . '\PHPMailer\src\SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Define your Gmail credentials
$gmail_user = 'anokye.wisfred22@gmail.com';        // 🔸 Replace with your Gmail
$gmail_pass = 'pmui urrx kbfh pnji';          // 🔸 Replace with Gmail App Password

// ===============================
// FORM HANDLING SECTION
// ===============================
$message_sent = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'] ?? '';
    $lastName  = $_POST['lastName'] ?? '';
    $email     = $_POST['email'] ?? '';
    $phone     = $_POST['phone'] ?? '';
    $projectType = $_POST['projectType'] ?? '';
    $budget    = $_POST['budget'] ?? '';
    $message   = $_POST['message'] ?? '';

    // Build the email body
    $body = "
        <h2>New Quote Request</h2>
        <p><strong>Name:</strong> {$firstName} {$lastName}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Project Type:</strong> {$projectType}</p>
        <p><strong>Budget:</strong> {$budget}</p>
        <p><strong>Message:</strong><br>{$message}</p>
    ";

    // Send email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'anokye.wisfred22@gmail.com';
        $mail->Password   = 'pmui urrx kbfh pnji';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($gmail_user, 'Wisfred Website');
        $mail->addAddress($gmail_user, 'Wisfred'); 

        $mail->isHTML(true);
        $mail->Subject = 'New Quote Request';
        $mail->Body    = $body;

        $mail->send();
        $message_sent = true;
    } catch (Exception $e) {
        $error_message = "Error sending message: " . $mail->ErrorInfo;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Building Engineer & Architect</title>
    <meta name="description" content="Get in touch with our professional building engineer and architect. Free consultations available for your architectural and engineering projects.">
    <link rel="stylesheet" href="./styles.css">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body>

<!-- ✅ SUCCESS OR ERROR MESSAGE -->
<?php if ($message_sent): ?>
<div style="background:#d1fae5;color:#065f46;padding:15px;text-align:center;">
    ✅ Your message has been sent successfully! We’ll get back to you soon.
</div>
<?php elseif ($error_message): ?>
<div style="background:#fee2e2;color:#991b1b;padding:15px;text-align:center;">
    ❌ <?= htmlspecialchars($error_message) ?>
</div>
<?php endif; ?>

<!-- Your full contact page starts here -->
<main style="padding-top: 5rem;">
    <section class="contact-hero">
        <div class="container">
            <div class="contact-hero-content">
                <h1 class="section-title">Get In Touch</h1>
                <p class="section-subtitle">
                    Ready to start your next project? Let's discuss how our architectural and 
                    engineering expertise can bring your vision to life.
                </p>
            </div>
        </div>
    </section>

    <section class="contact-content">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Form -->
                <div class="contact-form-section">
                    <h2 class="form-title">Send us a Message</h2>
                    <p class="form-subtitle">Fill out the form below and we'll get back to you within 24 hours.</p>
                    
                    <form method="POST" class="contact-form">
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
                                <label for="email">Email *</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="tel" id="phone" name="phone">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="projectType">Project Type</label>
                            <select id="projectType" name="projectType">
                                <option value="">Select a project type</option>
                                <option value="Residential">Residential</option>
                                <option value="Commercial">Commercial</option>
                                <option value="Industrial">Industrial</option>
                                <option value="Renovation">Renovation</option>
                                <option value="Consultation">Consultation</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="budget">Budget Range</label>
                            <select id="budget" name="budget">
                                <option value="">Select a budget range</option>
                                <option value="Under $100K">Under $100K</option>
                                <option value="$100K - $500K">$100K - $500K</option>
                                <option value="$500K - $1M">$500K - $1M</option>
                                <option value="$1M - $5M">$1M - $5M</option>
                                <option value="Over $5M">Over $5M</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">Project Description *</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">
                            Send Message <i data-lucide="send"></i>
                        </button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="contact-info-section">
                    <h2 class="info-title">Contact Information</h2>
                    <p><strong>Phone:</strong> +233(0)548749728</p>
                    <p><strong>Email:</strong> anokye.wisfred22@gmail.com</p>
                    <p><strong>Address:</strong> Kumasi, Ashanti Region, Ghana</p>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
lucide.createIcons();
</script>
</body>
</html>
