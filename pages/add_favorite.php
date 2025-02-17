<?php
include 'database.php'; 
$conn = ConnectDB(); 
$NutzerId = $_POST['NutzerId'];
$WohnungId = $_POST['WohnungId'];
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$query = "INSERT INTO favoriten (NutzerId, WohnungId) VALUES ($NutzerId, $WohnungId)";
if ($conn->query($query) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}
$conn->close();
?>