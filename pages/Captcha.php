<?php
session_start();
require "../includes/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверяем CAPTCHA
    $user_captcha = intval($_POST['captcha']);
    $correct_captcha = intval($_SESSION['captcha_result'] ?? 0);

    if ($user_captcha !== $correct_captcha) {
        echo "Fehler CAPTCHA! Versuchen Sie es erneut.";
        header('Refresh: 3; URL=../pages/KontaktSeite.php');
        exit;
    }

    // Данные из формы
    $name = trim($_POST['Name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $telefon = empty($telefon) ? null : $telefon;
    $nachricht = trim($_POST['nachricht'] ?? '');

    // Валидация
    if (empty($name) || empty($email) || empty($nachricht)) {
        echo "Einige Felder sind leer. Bitte füllen Sie alle Felder aus";
        header('Refresh: 3; URL=../pages/KontaktSeite.php');
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Bitte geben Sie eine gültige E-Mail-Adresse ein";
        header('Refresh: 3; URL=../pages/KontaktSeite.php');
        exit;
    }

    // Сохранение в базу данных
    $sql = 'INSERT INTO nachrichtkontseite (Name, Email, Telefon, Nachricht) VALUES (?, ?, ?, ?)';
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$name, $email, $telefon, $nachricht])) {
        echo "Ihre Nachricht wurde erfolgreich gesendet. Wir werden uns in Kürze bei Ihnen melden";
        header('Refresh: 3; URL=../index.php');
    } else {
        echo "Etwas ist schief gelaufen. Bitte versuchen Sie es später erneut";
        header('Refresh: 3; URL=../pages/KontaktSeite.php');
    }
    exit;
}
?>
