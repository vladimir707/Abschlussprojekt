
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Header with CSS Grid</title>
    <link rel="stylesheet" href="../styles/header.css">
    <link rel="stylesheet" href="../styles/footer_read_more.css">
</head>

<header class="header">
    <!-- Column 1: Logo -->
    <div class="logo-container">
        <div class='logo-circle'>
            <img src="../img/header_logo.jpg" alt="Website Logo" class="logo">
        </div>
    </div>

    <!-- Column 2: Navigation Buttons -->
    <nav class="nav-buttons">
        <a href="../index.php" class="nav-link">Home</a>
        <a href="../html/footer_read_more.html" class="nav-link">Über uns</a>
        <a href="../html/services.html" class="nav-link">Services</a>
        <a href="../pages/KontaktSeite.php" class="nav-link">Kontakt</a>
    </nav>

    <!-- Column 3: Icons -->
    <div class="icons-container">
        <a href="../pages/Meine_Anzeige_Index.php" class="icon">
            <div class="icon-circle">㊭</div>
            <span class="icon-label">Meine Anzeige</span>
        </a>
        <a href="../pages/Favoriten.php" class="icon">
            <div class="icon-circle">♡</div>
            <span class="icon-label">Favoriten</span>
        </a>
        <a href="login.php" class="icon">
            <div class="icon-circle">
                <img src="../img/header_login.jpg" alt="Login Icon" style="width: 80%; height: 80%; border-radius: 50%;">
            </div>
            <span class="icon-label">Anmeldung</span>
        </a>
    </div>
</header>

</html>