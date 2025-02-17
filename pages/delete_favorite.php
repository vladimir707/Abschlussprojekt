<?php
include 'database.php'; 
$conn = ConnectDB();
$NutzerId = $_POST['NutzerId'];
$WohnungId = $_POST['WohnungId'];
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$query = "DELETE FROM favoriten WHERE NutzerId = $NutzerId AND WohnungId = $WohnungId";
if ($conn->query($query) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}
$conn->close();
?>