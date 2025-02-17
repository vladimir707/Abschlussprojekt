let currentImageIndex = 0;


document.addEventListener("DOMContentLoaded", function() {
    const heartIcon = document.getElementById("heart-icon");
    if (isFavorite) {
        heartIcon.src = "../img/heart_marked.svg"; // Change to the marked heart icon
    } else {
        heartIcon.src = "../img/heart_unmarked.svg"; // Change to the unmarked heart icon
    }
    //heartIcon.addEventListener("click", anotherAction);
});
function addFavorite() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../pages/add_favorite.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    console.log(xhr.responseText);
                    response = JSON.parse(xhr.responseText);
                    console.log(response);
                    if (response.success) {
                        document.getElementById("heart-icon").src = "../img/heart_marked.svg";
                        console.log("Favorite added successfully");
                    } else {
                        console.error("Failed to add favorite:", response.error);
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", xhr.responseText);
                    console.error("Error name:", e.name);
                    console.error("Error message:", e.message);
                }
            } else {
                console.error("Request failed with status:", xhr.status);
            }
        }
    };
    console.log(`NutzerId=${NutzerId}&WohnungId=${WohnungId}`);
    xhr.send(`NutzerId=${NutzerId}&WohnungId=${WohnungId}`);
}

function removeFavorite() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../pages/delete_favorite.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    console.log(xhr.responseText);
                    response = JSON.parse(xhr.responseText);
                    console.log(response);
                    if (response.success) {
                        document.getElementById("heart-icon").src = "../img/heart_unmarked.svg";
                        console.log("Favorite removed successfully");
                    } else {
                        console.error("Failed to remove favorite:", response.error);
                    }
                } catch (e) {
                    console.error("Invalid JSON response:", xhr.responseText);
                    console.error("Error name:", e.name);
                    console.error("Error message:", e.message);
                }
            } else {
                console.error("Request failed with status:", xhr.status);
            }
        }
    };
    console.log(`NutzerId=${NutzerId}&WohnungId=${WohnungId}`);
    xhr.send(`NutzerId=${NutzerId}&WohnungId=${WohnungId}`);
}

function copyLink() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        showCustomModal();
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}
function showCustomModal() {
    var modal = document.getElementById("customModal");
    modal.style.display = "block";
    setTimeout(() => {
        modal.style.display = "none";
    }, 3000); // Hide the modal after 3 seconds
}
function anotherAction() {
    if (isFavorite) {
        removeFavorite();
        isFavorite = false;
        console.log("isFavorite: ", isFavorite);
    } else {
        addFavorite();
        isFavorite = true;
        console.log("isFavorite: ", isFavorite);
    }
}

function printPage() {
    window.print();
}

// Modal functionality
function openModal(index) {
    currentImageIndex = index;
    var modal = document.getElementById("myModal");
    var modalImg = document.getElementById("img01");
    modal.style.display = "block";
    console.log("Current Image Index:", currentImageIndex); // Log the current image index
    console.log("Image Source:", images[currentImageIndex]); // Log the image source
    modalImg.src = images[currentImageIndex];
}

function closeModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
}

function changeImage(direction) {
    currentImageIndex += direction;
    if (currentImageIndex >= images.length) {
        currentImageIndex = 0;
    } else if (currentImageIndex < 0) {
        currentImageIndex = images.length - 1;
    }
    var modalImg = document.getElementById("img01");
    console.log("Current Image Index:", currentImageIndex); // Log the current image index
    console.log("Image Source:", images[currentImageIndex]); // Log the image source
    modalImg.src = images[currentImageIndex];
}