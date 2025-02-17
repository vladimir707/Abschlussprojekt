<?php
header("Cache-Control: no-store, no-cache, must-revalidate, proxy-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php
//Verbindung mit der Databank erstellen
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'immobilien_db';
$conn = new mysqli($host, $username, $password, $database);
//Wenn Verbundung nicht erfolgleich ist, den weitere Code nicht ausführen
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Sitzung starten
session_start();

//TEST
//$_SESSION["user_id"]=3; 
//unset($_SESSION['user_id']);


$nutzer = 0;

// Überprüfung, ob die Va. Existiert
if (isset($_SESSION["user_id"])) {
    $nutzer = $_SESSION["user_id"];
}
$favoriten = [];

//Request zur Tabbelle Favoriten wohnungId für den bestimmte Nutezer zu bekommen
$favoriten_sql = "SELECT wohnungId FROM favoriten WHERE nutzerId = '" . $nutzer . "';";
//Iterarieren durch jede Zeile der Tabelle Favoriten und wohnungId zum Array $favoriten hinzugügen
$result_fav = $conn->query($favoriten_sql);
if ($result_fav->num_rows > 0) {
    while ($row_fav = $result_fav->fetch_assoc()) {
        $favoriten[] = $row_fav['wohnungId'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/index.css?v=1.0">
    <link rel="stylesheet" href="styles/filter_bar.css?v=1.0">
    <title>Document</title>
</head>
<header>
<div id="header-div">
            <?php include 'includes/header.php'; ?>
        </div>
    </header>
<body="body-main">


    <?php

    $filters = [];
    $params = [];
    $types = "";
    $stadt = "";
    $min_zim_zahl = "";
    $min_wohnflaeche = "";
    $min_kaltmiete = "";
    $max_zim_zahl = "";
    $max_wohnflaeche = "";
    $max_kaltmiete = "";
    $color_like_btn = "";

    //Grundbefehl  die Information von der Tabelle Wohnungen ausgeben(wie Vorlage)
    $sql = "SELECT wohnungId, titel,adresse,stadt,wohnflaeche,postleitzahl,kaltmiete,zimmerzahl FROM Wohnungen WHERE 1=1";

    ?>


    <?php
    //Wenn Post request mit  bestimmte Variabls, nicht leer ist, 
    //hinzufügen Werte zum Arrays  
    // um die Bedingungen und Parameter für die SQL-Abfrage dynamisch zu erstellen.
    if (!empty($_POST['stadt'])) {
        $stadt = $_POST['stadt'];
        $filters[] = "stadt LIKE ?";
        $params[] = "%" . $stadt . "%";
        $types .= "s";
    }
    if (!empty($_POST['min-kaltmiete'])) {
        $min_kaltmiete = $_POST['min-kaltmiete'];
        $filters[] = "kaltmiete >= ?";
        $params[] = (int)$min_kaltmiete;
        $types .= "i";
    }

    if (!empty($_POST['max-kaltmiete'])) {
        $max_kaltmiete = $_POST['max-kaltmiete'];
        $filters[] = "kaltmiete <= ?";
        $params[] = (int)$max_kaltmiete;
        $types .= "i";
    }

    if (!empty($_POST['min-wohnflaeche'])) {
        $min_wohnflaeche = $_POST['min-wohnflaeche'];
        $filters[] = "wohnflaeche >= ?";
        $params[] = (int)$min_wohnflaeche;
        $types .= "i";
    }

    if (!empty($_POST['max-wohnflaeche'])) {
        $max_wohnflaeche = $_POST['max-wohnflaeche'];
        $filters[] = "wohnflaeche <= ?";
        $params[] = (int)$max_wohnflaeche;
        $types .= "i";
    }
    if (!empty($_POST['min-zim-zahl'])) {
        $min_zim_zahl = $_POST['min-zim-zahl'];
        $filters[] = "zimmerzahl >= ?";
        $params[] = (int)$min_zim_zahl;
        $types .= "i";
    }

    if (!empty($_POST['max-zim-zahl'])) {
        $max_zim_zahl = $_POST['max-zim-zahl'];
        $filters[] = "zimmerzahl <= ?";
        $params[] = (int)$max_zim_zahl;
        $types .= "i";
    }
    //Wenn $filter-Array nicht leer ist
    if (!empty($filters)) {
        //Die Daten von Arrays,  mithilfe Separator zusammenfügen
        //Zusmmengefügte Ergebnis mit dem Grundbefehl zusammenfügen
        $sql .= " AND " . implode(" AND ", $filters);
    }

    $stmt = $conn->prepare($sql);

    //Eingeben Parameters in  SQL-Befehl
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();



    ?>
 
    <div id="main-content-container">
       
        <?php include 'includes/filter_bar.php'; ?>


        <!----------------------WohnungenList---------------------------------->
        <div class="scroll-list">
            <?php
            //Bekommen das Ergebniss von der Tabelle Wohnung
            $result = $stmt->get_result();
            //Wenn der Befehl etwas ergibt, iterieren durch jede Zeile 
            //und Die Information für jede Wohnung in HTML eingeben
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    //trim() entfernt Leerzeichen 
                    //Link zum Google Maps erstellen
                    //Parameters zusammenfügen
                    $adress_param = urlencode(trim($row['adresse']) . ',+' . trim($row['postleitzahl']) . ',+' . trim($row['stadt']));
                    //Hauptteil des Link mit Premeters konkatinieren
                    $adress_url = "https://www.google.com/maps/place/" . $adress_param;
                    //Wenn kein Nutzer angemeldet ist
                    //machen Like -Button grau
                    if ($nutzer == 0) {
                        $color_like_btn = "grey";

                    //Wenn Id von  diese Wohnung in Array Favoriten gibt als rot like-Button zeigen
                    } elseif (in_array($row['wohnungId'], $favoriten)) {
                        $color_like_btn = "red";

                    //Sonst orange
                    } else {
                        $color_like_btn = "orange";
                    }
                    echo '<div class="appartment-card" data-scroll="0">';

                    //Request alle Bilder, die zu der bestimmte Wohnung zuhöhren
                    $query_all_images = "SELECT BildLink,BildId, hauptbild FROM Bilder 
                        WHERE wohnungId='" . $row['wohnungId'] . "' ORDER BY hauptbild DESC";
                    $result2 = $conn->query($query_all_images);

                    //Durch Bilder, die current Wohnubg entsprechen iterieren und im Image-Scroller einfügen
                    if ($result2->num_rows > 0) {
                        echo  '<div class="img-scroller-container">';
                        echo '<div class="img-inner-container">';
                        while ($row2 = $result2->fetch_assoc()) {
                            echo '<img src="' . (substr($row2['BildLink'], 0, 3) === '../' ? substr($row2['BildLink'], 3) : $row2['BildLink']) . '" alt="image">';
                        }

                        echo '</div>';
                        echo '<svg class="arrow-left arrow" onclick="scrollImageStrip(event)" xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960" width="80px" fill="#173660"><path d="M576-240 336-480l240-240 51 51-189 189 189 189-51 51Z"/></svg>';
                        echo '<svg class="arrow-right arrow" onclick="scrollImageStrip(event)" xmlns="http://www.w3.org/2000/svg" height="80px" viewBox="0 -960 960 960" width="80px" fill="#173660"><path d="M522-480 333-669l51-51 240 240-240 240-51-51 189-189Z"/></svg>';
                        echo '<h1 class="count-scroll">1/' . $result2->num_rows . '</h1>';
                        echo '</div>';

                    //Wenn zur Wohnung kein Bild gehört
                    } else {
                        echo '<img src="../img/default.jpg"" alt="Kein Bild" class="default-img">';
                    }
                    echo '<div class="appartment-info">';
                    echo '<a href="pages/Beschreibung.php?wohnungId=' . $row['wohnungId'] . '"class="titel" target="blank"><h2>' . $row['titel'] . '</h2></a>';
                    echo '<a href="' . $adress_url . '" target="blank" class="adresse">' . $row['postleitzahl'] . ' ' . $row['stadt'] . ', ' . $row['adresse'] . '</a>';
                    echo '<div class="wohnfläche info-item">';
                    echo '<p >Wohnfläche</p>';
                    echo '<p>' . $row['wohnflaeche'] . 'm&sup2;</p>';
                    echo '</div>';
                    echo '<div class="kaltmiete info-item">';
                    echo '<p>Kaltmiete</p>';
                    echo '<p>' . $row['kaltmiete'] . '&euro;</p>';
                    echo '</div>';
                    echo '<div class="zim-zahl info-item">';
                    echo '<p>Zim.</p>';
                    echo '<p>' . $row['zimmerzahl'] . '</p>';
                    echo '</div>';

                    //Als dataset Daten vom SQL-request zu Javascript übergeben
                    echo '<svg class="info-icon like-btn" onclick="manageFavourites(event)" data-wohnungId="' . $row['wohnungId'] . '" data-nutzer="' . $nutzer . '" xmlns="http://www.w3.org/2000/svg" style="color:' . $color_like_btn . '" height="40px" viewBox="0 -960 960 960" width="30px" fill="currentColor"><path d="m480-144-50-45q-100-89-165-152.5t-102.5-113Q125-504 110.5-545T96-629q0-89 61-150t150-61q49 0 95 21t78 59q32-38 78-59t95-21q89 0 150 61t61 150q0 43-14 83t-51.5 89q-37.5 49-103 113.5T528-187l-48 43Zm0-97q93-83 153-141.5t95.5-102Q764-528 778-562t14-67q0-59-40-99t-99-40q-35 0-65.5 14.5T535-713l-35 41h-40l-35-41q-22-26-53.5-40.5T307-768q-59 0-99 40t-40 99q0 33 13 65.5t47.5 75.5q34.5 43 95 102T480-241Zm0-264Z"/></svg>';
                    echo '</div>';
                    echo '</div>';
                }
                //Wenn Request zur Tabelle Wohnungen kein Ergebnis gibt
            } else {
                echo '<p>Kein Ergebnis</p>';
            }
            ?>
        </div>

        <button id="scroll-up-btn"><svg xmlns="http://www.w3.org/2000/svg" height="48px" viewBox="0 -960 960 960" width="48px" fill="#FFFFFF">
                <path d="M480-554 283-357l-43-43 240-240 240 240-43 43-197-197Z" />
            </svg></button>

        <?php
        $data = json_decode(file_get_contents('php://input'), true);

        //Wenn es POST mit wohnugId gibt
        if (isset($data['wohnungId'])) {
            //Get the JSON data sent from JavaScript
            //Befehle für Löschen und Hinzufügen in Favoriten-Tabelle vorbereiten
            $add_to_favourite_sql = "INSERT INTO favoriten VALUES( '" . $nutzer . "','" . intval($data['wohnungId']) . "')";
            $delete_from_favourite_sql = "DELETE FROM favoriten WHERE nutzerId='" . $nutzer . "'&& wohnungId='" . intval($data['wohnungId']) . "'";
            //Wenn  den Wohnung-Id in Array-Favoriten gibt,ihm löschen
            if (!in_array($data['wohnungId'], $favoriten)) {
                if (!$conn->query($add_to_favourite_sql) === TRUE) {
                    echo "Error: "  . $conn->error;
                }
            //Wenn den Wohnung-Id in Array-Favoriten nicht gibt, hinzufügen
            } else {
                if (!$conn->query($delete_from_favourite_sql) === TRUE) {
                    echo "Error: "  . $conn->error;
                }
            }
        }

        ?>
    </div>
    <div id="footer-div">
        <?php include 'includes/footer.php'; ?>
    </div>

    <script src="js/index.js" defer></script>



    </body>

</html>
<?php $conn->close();?>