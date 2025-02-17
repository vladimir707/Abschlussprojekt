<?php
header('Content-Type: application/json');

// Подключение к базе данных
$host = "localhost";
$username = "root";
$password = "";
$database = "immobilien_db";
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB-Verbindungsfehler: " . $conn->connect_error]);
    exit;
}

// Abrufen von Anfragedaten
$input = json_decode(file_get_contents("php://input"), true);

// prüfen actijon und id
if (isset($input['action']) && $input['action'] === "delete" && isset($input['id'])) {
    $id = intval($input['id']);

    // starten der Transaktion
    $conn->begin_transaction();
    try {
        // Löschen von Bildern aus der Tabelle bilder
        $stmtBilder = $conn->prepare("DELETE FROM bilder WHERE WohnungId = ?");
        $stmtBilder->bind_param("i", $id);
        if (!$stmtBilder->execute()) {
            throw new Exception("Fehler beim Löschen aus der Tabelle 'bilder': " . $stmtBilder->error);
        }

        // löschen von Anzeigen aus der Tabelle wohnungen
        $stmtWohnungen = $conn->prepare("DELETE FROM Wohnungen WHERE WohnungId = ?");
        $stmtWohnungen->bind_param("i", $id);
        if (!$stmtWohnungen->execute()) {
            throw new Exception("Fehler beim Löschen aus der Tabelle 'Wohnungen': " . $stmtWohnungen->error);
        }

        // wenn alles erfolgreich war, dann commit
        $conn->commit();
        echo json_encode(["success" => true, "message" => "Anzeige und zugehörige Bilder erfolgreich gelöscht."]);
    } catch (Exception $e) {
        // im Fehlerfall rollback
        $conn->rollback();
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    } finally {
        $stmtBilder->close();
        $stmtWohnungen->close();
    }

    $conn->close();
    exit;
}

// wenn die Anfrage nicht korrekt ist
echo json_encode(["success" => false, "message" => "Ungültige Anfrage."]);
$conn->close();
exit;
?>
