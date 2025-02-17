<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "immobilien_db";


header('Content-Type: application/json; charset=utf-8');
session_start();

// zu Datenbank verbinden
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "DB-Verbindungsfehler: " . $conn->connect_error]));
}

// prüfen ob id vorhanden ist
$nutzerId = $_SESSION['nutzerId'] ?? null;
if (!$nutzerId) {
    echo json_encode(["success" => false, "message" => "Der Benutzer ist nicht autorisiert"]);
    exit;
}

// bearbeiten post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    try {
        $daten = $_POST;
        $BildLink = null;

        // die Datei hochladen
        if (isset($_FILES['file-input']) && $_FILES['file-input']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../img/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = pathinfo($_FILES['file-input']['name'], PATHINFO_FILENAME);
            $fileExt = pathinfo($_FILES['file-input']['name'], PATHINFO_EXTENSION);
            $uploadFile = $uploadDir . $fileName . '_' . uniqid() . '.' . $fileExt;

            if (!move_uploaded_file($_FILES['file-input']['tmp_name'], $uploadFile)) {
                throw new Exception("Fehler beim Hochladen der Datei");
            }
            $BildLink = $uploadFile;
        }

        $conn->begin_transaction();

        // erneuern tabelle
        if (isset($daten['WohnungId']) && !empty($daten['WohnungId'])) {
            $sql = "UPDATE Wohnungen SET Stadt = ?, Postleitzahl = ?, Adresse = ?, Zimmerzahl = ?, Wohnflaeche = ?, Etage = ?, Kaltmiete = ?, Nebenkosten = ?, Kaution = ?, Titel = ?, Beschreibung = ?, Haustiere = ?, Baujahr = ? WHERE WohnungId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "sisiiiiiissiii",
                $daten['Stadt'],
                $daten['Postleitzahl'],
                $daten['Adresse'],
                $daten['Zimmerzahl'],
                $daten['Wohnflaeche'],
                $daten['Etage'],
                $daten['Kaltmiete'],
                $daten['Nebenkosten'],
                $daten['Kaution'],
                $daten['Titel'],
                $daten['Beschreibung'],
                $daten['Haustiere'],
                $daten['Baujahr'],
                $daten['WohnungId']
            );

            if (!$stmt->execute()) {
                throw new Exception("Fehler beim Aktualisieren der Wohnungen: " . $stmt->error);
            }

            if ($BildLink) {
                $sql1 = "UPDATE bilder SET BildLink = ? WHERE WohnungId = ?";
                $stmt = $conn->prepare($sql1);
                $stmt->bind_param("si", $BildLink, $daten['WohnungId']);
                if (!$stmt->execute()) {
                    throw new Exception("Fehler beim Aktualisieren der Bilder: " . $stmt->error);
                }
            }
        } 
        // neue Zeile ins tabele hinzufügen
        else {
            $sql = "INSERT INTO Wohnungen (Stadt, Postleitzahl, Adresse, Zimmerzahl, Wohnflaeche, Etage, Kaltmiete, Nebenkosten, Kaution, Titel, Beschreibung, Haustiere, Baujahr, NutzerId, Wohnungstype) VALUES (?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "sisiiiiiissiiii",
               // "sisiiiiiissiiii",
                $daten['Stadt'],
                $daten['Postleitzahl'],
                $daten['Adresse'],
                $daten['Zimmerzahl'],
               $daten['Wohnflaeche'],
                $daten['Etage'],
                $daten['Kaltmiete'],
                $daten['Nebenkosten'],
                $daten['Kaution'],
                $daten['Titel'],
                $daten['Beschreibung'],
                $daten['Haustiere'],
                $daten['Baujahr'],
                $nutzerId,
                $daten['Wohnungstype']
            

            );
          
           
            if (!$stmt->execute()) {
                throw new Exception("Fehler beim Einfügen der Wohnungen: " . $stmt->error);
            }

            $wohnungId = $conn->insert_id;

            if ($BildLink) {
                $sql1 = "INSERT INTO bilder (BildLink, WohnungId) VALUES (?, ?)";
                $stmt = $conn->prepare($sql1);
                $stmt->bind_param("si", $BildLink, $wohnungId);
                if (!$stmt->execute()) {
                    throw new Exception("Fehler beim Einfügen der Bilder: " . $stmt->error);
                }
            }
        }

        $conn->commit();
        echo json_encode(["success" => true, "message" => "Daten erfolgreich gespeichert"]);
        } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }



}


