<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beschreibung</title>
    <link rel="stylesheet" href="../styles/beschreibung.css">
</head>
<body>

<?php include '../includes/header1.php'; ?>

<?php
include '../pages/database.php'; 
$conn = ConnectDB();
//$user_id = $_SESSION['user_id'];
$user_id = 1;
$wohnungId = 2;
if(isset($_GET['wohnungId']))
{
    $wohnungId=$_GET['wohnungId'];
    }
$isFavorit = isFavorite($conn, $user_id, $wohnungId); //true or false
$Beschreibung = getBeschreibung($conn, $wohnungId); //array of fields of the apartment
echo "<script>console.log('Beschreibung: " . json_encode($Beschreibung) . "');</script>";
$Bilder = getBilder($conn, $wohnungId); //array of images
?>
<!-- Embed PHP variables into JavaScript -->
<script>
    const images = <?php echo json_encode($Bilder); ?>;
    let isFavorite = <?php echo json_encode($isFavorit); ?>;
    const NutzerId = <?php echo json_encode($user_id); ?>;
    const WohnungId = <?php echo json_encode($wohnungId); ?>;
</script>
<script src="../js/beschreibung.js"></script>
<main>
    <div class="rectangle">
        <div class="large">
            <img src="<?php echo $Bilder[0]; ?>" alt="Image 1" onclick="openModal(0)">
        </div>
        <div class="small-top">
            <img src="<?php echo $Bilder[1]; ?>" alt="Image 2" onclick="openModal(1)">
        </div>
        <div class="small-bottom">
            <img src="<?php echo $Bilder[2]; ?>" alt="Image 3" onclick="openModal(2)">
        </div>
    </div>
    <div class="button-container">
        <button type="button" aria-label="Copy link" class="btn1" onclick="copyLink()">
            <img src="../img/clipboard.svg" alt="Copy link icon" class="svg-icon">
        </button>
        <button type="button" aria-label="Add to Favourites" class="btn2" onclick="anotherAction()">
            <img id="heart-icon" src="../img/heart_unmarked.svg" alt="Another action icon" class="svg-icon">
        </button>
        <button type="button" aria-label="Print page" class="btn3" onclick="printPage()">
            <img src="../img/print.svg" alt="Print page icon" class="svg-icon">
        </button>
    </div>
<div class="content-wrapper">
        <div class="container">
            <!-- Title -->
            <h3>Wohnung zur Miete</h3>

            <!-- Price Section -->
            <div class="price-section">
                <p><strong><?php echo $Beschreibung['Kaltmiete']; ?>€ </strong>Kaltmiete zzg Nebenkosten <strong><?php echo $Beschreibung['Nebenkosten']; ?>€</strong></p>
                <p><strong><?php echo $Beschreibung['Kaution']; ?>€ </strong>Kaution </p>
            </div>

            <!-- Features Section -->
            <div class="features-section">
                <div class="feature">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="svg-feature"><path d="M22.2 20.4h-3v-15c0-1.654-1.346-3-3-3H7.8c-1.654 0-3 1.346-3 3v15h-3a.6.6 0 0 0 0 1.2h20.4a.6.6 0 0 0 0-1.2m-4.2 0H6v-15c0-.992.806-1.8 1.8-1.8h8.4c.994 0 1.8.808 1.8 1.8zm-2.4-9.3a.9.9 0 1 0 0 1.8.9.9 0 0 0 0-1.8"></path></svg>
                    <div class="feature-item">
                        <span>   <strong><?php echo $Beschreibung['Zimmerzahl']; ?></strong> </span>
                        <span>Zimmer</span>
                    </div>
                </div>
                <div class="feature">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="svg-feature"><path d="M20.96 3.067a3.57 3.57 0 0 0-2.537-1.058H5.595V2c-.957 0-1.858.371-2.536 1.049A3.57 3.57 0 0 0 2 5.582V18.4a3.58 3.58 0 0 0 3.576 3.592h7.664c.353 0 .632-.279.632-.622s-.288-.631-.632-.631h-3.168v-3.053a.63.63 0 0 0-.185-.446.632.632 0 0 0-1.078.445v3.054H5.576a2.28 2.28 0 0 1-1.644-.687 2.32 2.32 0 0 1-.678-1.643V5.592c.01-.622.25-1.207.697-1.643a2.3 2.3 0 0 1 1.644-.678h3.233l-.01 9.94a.632.632 0 0 0 1.264 0v-3.146h3.511a.632.632 0 0 0 0-1.262h-3.511V3.27h8.323c.622 0 1.207.251 1.644.687s.678 1.021.678 1.643v3.202H17.44a.63.63 0 0 0-.446.185.625.625 0 0 0 0 .891c.12.121.279.186.446.186h3.288v8.353c0 1.28-1.04 2.32-2.331 2.32h-.586c-.353 0-.631.278-.631.63s.288.632.631.632h.595c.957 0 1.858-.371 2.536-1.049A3.57 3.57 0 0 0 22 18.418V5.61a3.5 3.5 0 0 0-1.04-2.533z"></path></svg>    
                    <div class="feature-item">
                        <span>
                        <strong><?php echo $Beschreibung['Wohnflaeche']; ?></strong> </span>
                        <span>WohnFläche</span>
                    </div> 
                </div>
                <div class="feature">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="svg-feature"><path d="M16.444 4.556c0-.306.25-.556.556-.556h4.444c.306 0 .556.25.556.556 0 .305-.25.555-.556.555h-3.888V9c0 .306-.25.556-.556.556h-4.444v5c0 .305-.25.555-.556.555H7.556V19c0 .306-.25.556-.556.556H2.556A.557.557 0 0 1 2 19c0-.306.25-.556.556-.556h3.888v-3.888c0-.306.25-.556.556-.556h4.444V9c0-.306.25-.556.556-.556h4.444z"></path></svg>
                    <div class="feature-item">
                        <span><strong><?php echo $Beschreibung['Etage']; ?>.</strong></span>
                        <span>Geschoss</span>
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="address-section" onclick="openInGoogleMaps()">
                <h5>
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="svg-adresse" data-testid="aviv.CDP.Sections.Location.Address.Icon"><path d="M11.111 21.12C9.154 18.712 4.8 12.877 4.8 9.6a7.2 7.2 0 1 1 14.4 0c0 3.277-4.387 9.112-6.311 11.52a1.133 1.133 0 0 1-1.778 0M12 12c1.324 0 2.4-1.076 2.4-2.4S13.324 7.2 12 7.2a2.4 2.4 0 0 0-2.4 2.4c0 1.324 1.076 2.4 2.4 2.4"></path></svg>
                Adresse:<?php echo $Beschreibung['Stadt']." ".$Beschreibung['Postleitzahl']." ".$Beschreibung['Adresse']; ?>
                </h5>          
            </div>

            <!-- Description Section -->
            <div class="description-section">
                <h3>Objektbeschreibung</h3>
                <p><?php echo $Beschreibung['Beschreibung']; ?></p>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <div class="contact-header">
                <div class="profile-pic">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
    <path d="M0 0h24v24H0z" fill="none"/>
    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
</svg>
                </div>
                <h3><?php echo $Beschreibung['Vorname']." ".$Beschreibung['Nachname']; ?> </h3>
            </div>
            <div class="contact-info">
                <button><?php echo $Beschreibung['Email']; ?></button>
            <div class="contact-info">
                <button>+4915134523445</button>
                </div>   
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
<div id="myModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="img01">
    <a class="prev" onclick="changeImage(-1)">&#10094;</a>
    <a class="next" onclick="changeImage(1)">&#10095;</a>
</div>

<!-- Custom Modal Structure -->
<div id="customModal">
    <div class="content">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle">
            <path d="M9 11l3 3L22 4"></path>
            <path d="M22 12a10 10 0 1 1-10-10"></path>
        </svg>
        <p>Der Link wurde in der Zwischenablage gespeichert</p>
    </div>
</div>

<script>
function openInGoogleMaps() {
    const address = "<?php echo $Beschreibung['Adresse'] . ', ' . $Beschreibung['Postleitzahl'] . ' ' . $Beschreibung['Stadt']; ?>";
    const url = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`;
    window.open(url, '_blank');
}
</script>


</main>
<?php include '../includes/footer1.php'; ?>
</body>
</html>