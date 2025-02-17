<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'immobilien_db';

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get WohnungId from the POST request
if (isset($_POST['WohnungId'])) {
    $wohnungId = intval($_POST['WohnungId']); // Ensure it's an integer to prevent SQL injection

    // SQL query to remove from favorites
    $sql = "DELETE FROM favoriten WHERE WohnungId = ?";

    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $wohnungId);
    $stmt->execute();

    // Close the statement and connection
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
