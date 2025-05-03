<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    # FIX: Replace this email with recipient email
    $mail_to = "info@colakhealth.com";

    # Sender Data
    $name = str_replace(array("\r", "\n"), array(" ", " "), strip_tags(trim($_POST["full-name"])));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";
    $service = isset($_POST["service"]) ? trim($_POST["service"]) : "";
    $message = trim($_POST["message"]);

    if (empty($name) or !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        # Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Please complete the required fields and try again.";
        exit;
    }

    # Mail Content
    $content = "Name: $name\n";
    $content .= "Email: $email\n";

    if (!empty($phone)) {
        $content .= "Phone: $phone\n";
    }

    if (!empty($service)) {
        $content .= "Service: $service\n";
    }

    if (!empty($message)) {
        $content .= "\nMessage:\n$message\n";
    }

    # Email subject
    $subject = "New Inquiry from $name - Colak Health Management";

    # Email headers
    $headers = "From: $name <$email>";

    # Send the email
    $success = mail($mail_to, $subject, $content, $headers);
    if ($success) {
        # Set a 200 (okay) response code
        http_response_code(200);
        echo "Thank you! Your message has been sent. We'll contact you shortly.";
    } else {
        # Set a 500 (internal server error) response code
        http_response_code(500);
        echo "Oops! Something went wrong, we couldn't send your request. Please try again or call us directly.";
    }

} else {
    # Not a POST request, set a 403 (forbidden) response code
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}

?>