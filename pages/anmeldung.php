<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = 'localhost';
    $db_name = 'immobilien_db';
    $username = 'root';
    $password = ''; 

    try {
        $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Verbindungsfehler: " . $e->getMessage());
    }

    // Daten vom Formular empfangen
    $vorname = trim($_POST['vorname']);
    $nachname = trim($_POST['nachname']);
    $email = trim($_POST['email']);
    $kennwort = $_POST['kennwort'];

    // Per E-Mail auf Duplikate prüfen
    $query = "SELECT * FROM nutzer WHERE Email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Если пользователь уже существует, выводим сообщение об ошибке
        echo "<script>
                window.onload = function() {
                    document.getElementById('errorMessage').innerText = 'Ein Benutzer mit dieser E-Mail-Adresse existiert bereits.';
                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();
                };
              </script>";
    } else {
        $hashed_password = password_hash($kennwort, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO nutzer (Vorname, Nachname, Email, Kennwort) 
                  VALUES (:vorname, :nachname, :email, :kennwort)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':vorname', $vorname);
        $stmt->bindParam(':nachname', $nachname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':kennwort', $hashed_password);

        if ($stmt->execute()) {
            // Einrichten einer Sitzung für einen neuen Benutzer
            $_SESSION['user_id'] = $conn->lastInsertId();
            $_SESSION['name'] = $vorname . " " . $nachname;
            $_SESSION['email'] = $email;

            header("Location: Profil.php");
            exit;
        } else {
            echo "<script>
                    window.onload = function() {
                        document.getElementById('errorMessage').innerText = 'Registrierungsfehler. Bitte versuchen Sie es später erneut.';
                        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        errorModal.show();
                    };
                  </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../styles/anmeldung.css">
    <title>Registrierung</title>
</head>
<body>
   
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Fehler</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="errorMessage"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="left-side">
            <div class="card">
                <div class="card-header text-center">
                    <img src="../img/Logo2.jpg" alt="Logo" class="logo">
                    <div>Erstelle deinen Account</div>
                </div>
                <div class="card-body">
                    <form action="anmeldung.php" method="POST">
                        <div class="mb-3">
                            <label for="vorname" class="form-label">Vorname*</label>
                            <input type="text" id="vorname" name="vorname" class="form-control" required placeholder="Bitte Vorname eingeben">
                        </div>
                        <div class="mb-3">
                            <label for="nachname" class="form-label">Nachname*</label>
                            <input type="text" id="nachname" name="nachname" class="form-control" required placeholder="Bitte Nachname eingeben">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-Mail-Adresse*</label>
                            <input type="email" id="email" name="email" class="form-control" required placeholder="Bitte E-Mail-Adresse eingeben">
                        </div>
                        <div class="mb-3">
                            <label for="kennwort" class="form-label">Passwort*</label>
                            <input type="password" id="kennwort" name="kennwort" class="form-control" required placeholder="Bitte Passwort eingeben">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">Fortfahren</button>
                    </form>

                    <div class="divider-container">
                        <span class="divider-line"></span>
                        <span class="divider-text">oder</span>
                        <span class="divider-line"></span>
                    </div>

                    <a href="login.php" class="btn btn-outline-secondary w-100 mt-3">Jetzt anmelden</a>
                </div>
            </div>
        </div>
        <div class="right-side">
            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
