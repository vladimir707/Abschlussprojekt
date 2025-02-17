<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Profil</title>
    <style>
        /* Пастельные цвета */
        body {
            background-color: #F0EAD2; /* Пастельный бежевый фон */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 50px;
            padding: 20px;
        }

        .card {
            
            border: 1px solid #ADC178; /* Зеленый бордер */
            border-radius: 10px;
            padding: 20px;
        }

        .card-header {
            background-color: #A98467; /* Пастельный коричневый для шапки */
            color: #fff;
            border-radius: 10px 10px 0 0;
            padding: 10px;
            text-align: center;
        }

        .btn-logout {
            background-color: #6C584C; /* Темный коричневый для кнопки logout */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            display: inline-block;
            text-decoration: none;
        }

        .btn-logout:hover {
            background-color: #A98467; /* Коричневый при наведении */
        }

        h1 {
            text-align: center;
            color: #6C584C; /* Темный коричневый для заголовка */
        }

        p {
            color: #6C584C; /* Темный коричневый для текста */
            text-align: center;
        }
    </style>
</head>
<body> 
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2>Willkommen in Ihrem Profil</h2>
            </div>
            <div class="card-body">
                <h1> <?php echo htmlspecialchars($_SESSION['name']); ?>!</h1>
                <p>Ihr Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
                <a href="logout.php" class="btn-logout">Ausloggen</a>
            </div>
        </div>
    </div>
</body> 
</html>