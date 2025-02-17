<?php
session_start();

// Set user_id in session
$_SESSION['user_id'] = 1;
$nutzerId = $_SESSION['user_id']; // Session for logged-in user

// Database configuration
$host = 'localhost';       // Database host
$username = 'root';         // Database username
$password = '';             // Database password
$database = 'immobilien_db'; // Database name

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Fetch data from the "favoriten", "bilder", and "wohnungen" tables
$sql = "SELECT favoriten.NutzerID, favoriten.WohnungId, GROUP_CONCAT(bilder.BildLink) AS BildLinks, 
       MAX(wohnungen.Stadt) AS Stadt, MAX(wohnungen.Adresse) AS Adresse, 
       MAX(wohnungen.Wohnflaeche) AS Wohnflaeche, MAX(wohnungen.Kaltmiete) AS Kaltmiete, 
       MAX(wohnungen.Zimmerzahl) AS Zimmerzahl
        FROM favoriten
        INNER JOIN bilder ON favoriten.WohnungId = bilder.WohnungId
        INNER JOIN wohnungen ON favoriten.WohnungId = wohnungen.WohnungId
       GROUP BY favoriten.NutzerID, favoriten.WohnungId";

// Execute the query and store the result
$result = $conn->query($sql);

// Start HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" rel="stylesheet">
    <title>Favorites</title>
    <style>
        .property-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        /* Position arrows only within their gallery */
        .swiper-container {
            position: relative; /* This is necessary for arrows to be positioned within the container */
        }

        .swiper-button-next,
        .swiper-button-prev {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
        }

        /* Arrows are unique to each slider */
        .swiper-button-next {
            right: 10px;
        }
        .swiper-button-prev {
            left: 10px;
        }

        .swiper-slide img {
    width: 100%;
    height: auto;
    object-fit: cover; /* Можно поменять на 'contain', 'fill' или 'none' в зависимости от потребностей */
}

    .container_my-4 {
        margin-top: 20px;
     
    margin: 20px auto;
    padding: 20px;

    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;

    }


    .col-md-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }

        .property-info {
            padding: 15px;
        }
        .property-title {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .property-details span {
            display: inline-block;
            margin-right: 15px;
        }
        .swiper-button-next, .swiper-button-prev {
            color: #000;
        }
        .swiper-pagination-bullet-active {
            background: #007bff;
        }
        .favorite-heart-container {
            position: relative;
            display: inline-block;
            margin-bottom: 10px;
        }
        .tooltip {
            display: none;
            position: absolute;
            bottom: 100%;
            left: 200%;
            transform: translateX(-50%);
            background-color: #ffcc00;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 10;
            opacity: 0;
            transition: opacity 0.2s;
        }
        .favorite-heart-container:hover .tooltip {
            display: block;
            opacity: 1;
        }
        .favorite-heart {
            color:rgb(230, 25, 73) !important;
            font-size: 20px;
            cursor: pointer;
        }
        .location-icon {
            color: #007bff; /* Blue color */
            margin-right: 5px;
        }
        .address-link {
            color: #007bff;
            text-decoration: none;
        }
        .address-link:hover {
            text-decoration: underline;
            color: #0056b3;
        }
        .footer-div {
            display: flex;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<div id="header-div">
            <?php include '../includes/header1.php'; ?>
        </div>
<body>
<div class="container_my-4">
    <h1>My Favorites</h1>
    <div class="row">
    <?php
    if ($result->num_rows > 0) {
        $swiperIndex = 0; // Index for uniqueness
        while ($row = $result->fetch_assoc()) {
            $images = explode(',', $row['BildLinks']);
            ?>
            <div class="col-md-4">
                <div class="property-card">
                    <div class="swiper-container swiper-<?php echo $swiperIndex; ?>">
                        <div class="swiper-wrapper">
                            <?php foreach ($images as $image) { ?>
                                <div class="swiper-slide">
                                    <img src="<?php echo htmlspecialchars($image); ?>" alt="Property Image">
                                </div>
                            <?php } ?>
                        </div>
                        <!-- Add Pagination -->
                        <div class="swiper-pagination swiper-pagination-<?php echo $swiperIndex; ?>"></div>
                        <!-- Add Navigation -->
                        <div class="swiper-button-next swiper-button-next-<?php echo $swiperIndex; ?>"></div>
                        <div class="swiper-button-prev swiper-button-prev-<?php echo $swiperIndex; ?>"></div>                        
                    </div>
                    <div class="property-info">
                        <div class="property-title">
                            <a href="Beschreibung.php?WohnungId=<?php echo $row['WohnungId']; ?>">
                                <?php echo $row['Zimmerzahl']; ?>-Room Apartment for Rent, <?php echo $row['Stadt']; ?>
                            </a>
                        </div>
                            <div class="favorite-heart-container">
                                <span class="favorite-heart" onclick="removeFavorite(<?php echo $row['WohnungId']; ?>)">&#10084;</span>
                                <span class="tooltip">Remove from favorites</span>
                            </div>
                        <p>
                            <i class="fas fa-map-marker-alt location-icon"></i>
                            <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($row['Adresse']); ?>" 
                               target="_blank" 
                               class="address-link">
                                <?php echo htmlspecialchars($row['Adresse']); ?>
                            </a>
                        </p>
                        <div class="property-details">
                            <span>Living Area: <?php echo $row['Wohnflaeche']; ?>m²</span>
                            <span>Rent: <?php echo $row['Kaltmiete']; ?>€</span>
                            <span>Rooms: <?php echo $row['Zimmerzahl']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    new Swiper('.swiper-<?php echo $swiperIndex; ?>', {
                        loop: true,
                        navigation: {
                            nextEl: '.swiper-button-next-<?php echo $swiperIndex; ?>',
                            prevEl: '.swiper-button-prev-<?php echo $swiperIndex; ?>',
                        },
                        pagination: {
                            el: '.swiper-pagination-<?php echo $swiperIndex; ?>',
                            clickable: true,
                        },
                    });
                });
            </script>
            <?php
            $swiperIndex++;
        }
    } else {
        echo "<p>No favorites found.</p>";
    }
    ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
<script>
    // JavaScript function to handle removing from favorites
    function removeFavorite(wgId) {
        if (confirm('Are you sure you want to remove this listing from your favorites?')) {
            // Make an AJAX request to remove the favorite
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "remove_favorite.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Reload the page to reflect the changes
                    window.location.reload();
                }
            };
            xhr.send("WohnungId=" + wgId);
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swipers = document.querySelectorAll('.swiper-container');
        swipers.forEach(container => {
            new Swiper(container, {
                loop: true,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        });
    });
</script>
</body>

<div id="footer-div">
            <?php include '../includes/footer1.php'; ?>
        </div>  
</html>
<?php
// Close the database connection
$conn->close();
?>
