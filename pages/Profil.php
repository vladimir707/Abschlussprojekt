<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/stylesProfil.css">
</head>

<body>

    <header>
        <?php  include("../includes/header1.php");  ?>
    </header>

    <main>
        <?php
        session_start();

        // Проверка наличия nutzerId в сессии
        if (!isset($_SESSION['nutzerId'])) {
            header("Location: login.php");
            exit;
        }

        $nutzerId = $_SESSION['nutzerId'];

        // Подключение к базе данных
        $mysqli = new mysqli('localhost', 'root', '', 'immobilien_db');
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Подготовленный запрос для получения данных пользователя
        $stmt = $mysqli->prepare("SELECT Vorname, Nachname, Email FROM nutzer WHERE nutzerId = ?");
        if ($stmt) {
            $stmt->bind_param("i", $nutzerId);
            $stmt->execute();
            $stmt->bind_result($vorname, $nachname, $email);

            // Проверка наличия результата
            if (!$stmt->fetch()) {
                echo "<p class='text-danger'>Keine Daten für NutzerId $nutzerId gefunden.</p>";
                $vorname = $nachname = $email = $telefon = "Nicht verfügbar";
            }

            $stmt->close();
        } else {
            echo "<p class='text-danger'>Fehler beim SQL-Query: " . $mysqli->error . "</p>";
        }

        // Закрытие соединения с базой данных
        $mysqli->close();
        ?>

        <section class="grid">
            <div class="items">
                <div class="welcome-banner">
                    <h2>Willkommen in Ihrem Profil</h2>
                </div>
            </div>
            <div class="items">
                <div class="btn-block">
                    <button class="custom-button" onclick="window.location.href='../pages/Meine_Anzeige_Index.php'">Meine Anzeige</button> <br>
                    <button class="custom-button" onclick="window.location.href='../pages/favoriten.php'">Meine Favoriten</button> <br>
                    <button class="custom-button" onclick="window.location.href='../pages/logout.php'">Ausloggen</button>
                </div>
            </div>
            <div class="items">
                <div class="card-body">
                    <h2>Persönliche Informationen</h2>
                    <p class="info-item"><strong>Vorname:</strong> <?php echo htmlspecialchars($vorname); ?></p>
                    <p class="info-item"><strong>Nachname:</strong> <?php echo htmlspecialchars($nachname); ?></p>
                    <p class="info-item"><strong>E-Mail:</strong> <?php echo htmlspecialchars($email); ?></p>

                </div>
            </div>
        </section>
    </main>

    <footer>
            <?php  include("../includes/footer1.php");  ?>
    </footer>

</body>

</html>