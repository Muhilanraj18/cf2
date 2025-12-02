<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Set content type to JSON
header('Content-Type: application/json');

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get and sanitize form data
    $name = isset($_POST['name']) ? strip_tags(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $subject = isset($_POST['_subject']) ? strip_tags(trim($_POST['_subject'])) : '';
    $message = isset($_POST['message']) ? strip_tags(trim($_POST['message'])) : '';
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in all fields.']);
        exit;
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email address.']);
        exit;
    }
    
    // Set recipient email
    $to = "info@craftedclipz.in";
    
    // Set email subject
    $email_subject = "New Contact from Website: " . $subject;
    
    // Build email content
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Subject: $subject\n\n";
    $email_content .= "Message:\n$message\n";
    
    // Set email headers
    $headers = "From: $name <$email>\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email
    if (mail($to, $email_subject, $email_content, $headers)) {
        echo json_encode(['success' => true, 'message' => 'Message sent successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again.']);
    }
    
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>