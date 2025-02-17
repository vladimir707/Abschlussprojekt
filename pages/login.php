<?php
session_start();

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

    $email = $_POST['email'];
    $input_password = $_POST['kennwort'];

    $query = "SELECT * FROM nutzer WHERE Email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Проверка пароля
        if (password_verify($input_password, $user['Kennwort'])) {
            $_SESSION['nutzerId'] = $user['NutzerId'];
            $_SESSION['vorname'] = $user['Vorname'];
            $_SESSION['nachname'] = $user['Nachname'];
            $_SESSION['email'] = $user['Email'];

            header("Location: Profil.php");
            exit;
        } else {
            echo "<script>
                    window.onload = function() {
                        document.getElementById('errorMessage').innerText = 'Ungültiges Passwort. Versuchen Sie es erneut.';
                        var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                        errorModal.show();
                    };
                  </script>";
        }
    } else {
        echo "<script>
                window.onload = function() {
                    document.getElementById('errorMessage').innerText = 'Ein Benutzer mit dieser E-Mail wurde nicht gefunden.';
                    var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                    errorModal.show();
                };
              </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/login.css">
    <title>Login</title>
</head>
<body>
<div class="container">
    <div class="left-side">
        <div class="card">
            <div class="card-header text-center">
                <img src="../img/Logo2.jpg" alt="Логотип" class="logo">
                <div>Willkommen zurück!</div>
            </div>
            <div class="card-body">
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="email">E-Mail-Adresse</label>
                        <input type="email" id="email" name="email" class="form-control" required placeholder="Bitte E-Mail-Adresse eingeben">
                    </div>
                    <div class="form-group">
                        <label for="kennwort">Passwort</label>
                        <input type="password" id="kennwort" name="kennwort" class="form-control" required placeholder="Bitte Passwort eingeben">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block mb-3">Anmelden</button><br>
                </form>

                <div>
                    <button type="submit" class="btn btn-primary btn-block" onclick="window.location.href='anmeldung.php'">Jetzt kostenfrei registrieren</button>
                </div>
            </div>
        </div>
    </div>
    <div class="right-side"></div>
</div>


<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="errorModalLabel">Fehler</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="errorMessage">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Schließen</button>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
