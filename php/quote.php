<?php
// Database connection parameters
$servername = "localhost";
$username = "u253184498_savat";
$password = "Stavat@123";
$dbname = "u253184498_artventure";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

// Check if it's a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $propertyType = $conn->real_escape_string($_POST['propertyType']);
    $wallCount = (int)$_POST['wallCount'];
    $totalArea = (float)$_POST['totalArea'];

    // SQL query to insert data
    $sql = "INSERT INTO quotations (name, email, phone, address, property_type, wall_count, total_area, created_at)
            VALUES ('$name', '$email', '$phone', '$address', '$propertyType', $wallCount, $totalArea, NOW())";

    // Execute query and check if successful
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Quotation submitted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

// Close connection
$conn->close();
?>