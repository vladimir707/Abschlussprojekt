<?php
include '../includes/db_connection.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['link'], $data['wohnungId'])) {
        $link = $data['link'];
        $wohnungId = intval($data['wohnungId']);

        // SQL для удаления изображения
        $sql = "DELETE FROM bilder WHERE BildLink = ? AND WohnungId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $link, $wohnungId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => $stmt->error]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "Ungültige Daten"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Ungültige Anfrage"]);
}
?>
