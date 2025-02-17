<?php
// Datenbankverbindung herstellen
$host = "localhost";
$username = "root";
$password = "";
$database = "immobilien_db";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die('Die Verbindung zur Datenbank konnte nicht aufgebaut werden: ' . $conn->connect_error);
}

session_start();

//$_SESSION['user_id']=4;
$nutzerId = $_SESSION['nutzerId']; // Session für eingeloggten Nutzer
//$_SESSION['nutzerId'] = $user['NutzerId'];

//echo $nutzerId;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // prüfen ob id vorhanden ist
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);

        $sql = "SELECT wohnungen.*, 
        GROUP_CONCAT(bilder.BildLink SEPARATOR ',') AS BildLinks
 FROM wohnungen
 LEFT JOIN bilder ON bilder.WohnungId = wohnungen.WohnungId
 WHERE NutzerId = ?
 GROUP BY wohnungen.WohnungId";


        $sql = "SELECT wohnungen.*, bilder.BildLink 
                FROM wohnungen 
                LEFT JOIN bilder ON bilder.WohnungId = wohnungen.WohnungId 
                WHERE wohnungen.WohnungId = ? AND  wohnungen.NutzerId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id, $nutzerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $anzeige = $result->fetch_assoc();
        
        header('Content-Type: application/json');
        echo json_encode($anzeige);
    } else {
        // alle anzeigen
        $sql = "SELECT wohnungen.*, 
        GROUP_CONCAT(bilder.BildLink SEPARATOR ',') AS BildLinks
                FROM wohnungen 
                LEFT JOIN bilder ON bilder.WohnungId = wohnungen.WohnungId 
 WHERE NutzerId = ?
 GROUP BY wohnungen.WohnungId";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $nutzerId);
        $stmt->execute();
        $result = $stmt->get_result();

        $anzeigen = [];
        while ($row = $result->fetch_assoc()) {
            $anzeigen[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($anzeigen);
    }
    exit;
}




?>

