<?php
include '../includes/db_connection.php'; 

$uploadDir = '../img/'; // Папка для сохранения изображений
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true); // Создать папку, если её нет
}

$response = ['success' => false, 'files' => []];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверяем наличие значения WohnungId
    if (isset($_POST['wohnungId']) && !empty($_POST['wohnungId'])) {
        $wohnungId = intval($_POST['wohnungId']); // Преобразуем в целое число

        // Проверяем, были ли загружены файлы
        if (!empty($_FILES['images']['name'][0])) {
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                $fileName = uniqid() . "_" . basename($_FILES['images']['name'][$key]); // Уникальное имя файла
                $filePath = $uploadDir . $fileName;

                // Перемещение загруженного файла
                if (move_uploaded_file($tmpName, $filePath)) {
                    // Сохранение ссылки в базе данных
                    $sql = "INSERT INTO bilder (WohnungId, BildLink) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);

                    if ($stmt) {
                        $stmt->bind_param("is", $wohnungId, $filePath);
                        if ($stmt->execute()) {
                            $response['files'][] = $filePath;
                        } else {
                            error_log("Fehler beim Speichern в базе данных: " . $stmt->error);
                        }
                    } else {
                        error_log("Fehler при подготовке запроса: " . $conn->error);
                    }
                } else {
                    error_log("Fehler beim Verschieben der Datei: $filePath");
                }
            }
            $response['success'] = true;
        } else {
            $response['error'] = "Keine Dateien ausgewählt.";
        }
    } else {
        $response['error'] = "WohnungId не задан.";
    }
} else {
    $response['error'] = "Неверный метод запроса.";
}

header('Content-Type: application/json');
echo json_encode($response);



