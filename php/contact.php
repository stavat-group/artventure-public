<?php
header('Content-Type: application/json');

try {
    // Get the JSON _POST from the request
   

    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
        throw new Exception('Please fill in all required fields.');
    }

    // Validate email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Please enter a valid email address.');
    }

    // _POSTbase connection parameters
    $host = 'localhost';
    $dbname = 'u253184498_artventure';
    $username = 'u253184498_savat';
    $password = 'Stavat@123';

    // Create _POSTbase connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO contacts (name, email, phone, message, created_at) 
                           VALUES (:name, :email, :phone, :message, NOW())");

    // Execute the statement with sanitized _POST
    $stmt->execute([
        ':name' => $_POST['name'],
        ':email' => $_POST['email'],
        ':phone' => $_POST['phone'] ?? '',
        ':message' => $_POST['message']
    ]);

    // Send success response
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for your message. We will get back to you soon!'
    ]);

} catch (Exception $e) {
    // Send error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>