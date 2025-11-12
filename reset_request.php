<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include the PHPMailer autoloader
require 'vendor/autoload.php';

$host = 'localhost';
$db_username = 'root';
$password = '';
$database = 'lifelinelocator';

// Create a MySQLi connection
$conn = new mysqli($host, $db_username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Validate if the email exists
    $stmt = $conn->prepare("SELECT * FROM signup WHERE email = ?");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));

        // Store the token in the database
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();

        // Send email with the reset link using PHPMailer
        $resetLink = "http://yourwebsite.com/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: " . $resetLink;

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Gmail SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'erandihansini4@gmail.com';  // Your Gmail address
            $mail->Password = 'idmk iqjf qcrp hnrj';  // App password (if 2FA enabled)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;  // TCP port to connect to

            //Recipients
            $mail->setFrom('erandihansini4@gmail.com', 'Your Website');
            $mail->addAddress($email);  // Add a recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            // Send the email
            $mail->send();
            echo "A reset link has been sent to your email.";
        } catch (Exception $e) {
            echo "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email not found.";
    }

    if ($stmt->execute()) {
        echo "link has been sent to email successfully";

        // Redirect to the login page
        header("Location: reset_password.html");
        exit(); // Ensure no further code is executed after the redirect
    } else {
        echo "Failed: " . $stmt->error;
    }
    $stmt->close();

    $stmt->close();
}

$conn->close();
?>
