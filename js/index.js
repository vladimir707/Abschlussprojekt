// Button-Element holen und initial verstecken
let scrollUpBtn = document.getElementById('scroll-up-btn');
scrollUpBtn.classList.add('hidden');
// Timer für Debouncing
let debounceTimer;
window.addEventListener("scroll", () => {
    //Timer zurücksetzen
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        // Button anzeigen, wenn mehr als eine Fensterhöhe gescrollt wurde
        if (window.scrollY >= window.innerHeight) {
            scrollUpBtn.classList.remove("hidden");
            //console.log(window.scrollY);
        }
        // Button verstecken, wenn weniger gescrollt wurde
        if (window.scrollY < window.innerHeight) {
            scrollUpBtn.classList.add("hidden");
        }
    }, 50);

});


// Klick-Event-Listener für den Button
scrollUpBtn.addEventListener("click", (e) => {
    e.preventDefault();
    //Scrollen zu Position 100px, 
    window.scrollTo({
        top: 100,
        // Glattes Scrollen
        behavior: "smooth"
    });
})


function manageFavourites(event) {
    event.preventDefault();
    // Holt den Button und die zugehörige Wohnungs-ID aus der HTML-Struktur
    let likeBtn = event.target.closest('.appartment-card').querySelector('.like-btn');
    let nutzer = likeBtn.dataset.nutzer;

    //Weitere Code nur wenn nutzer>0 ausführen(d.h. wenn jemanden angemeldet ist)
    if (nutzer > 0) {

        //holt die zugehörige Wohnungs-ID
        let wohnungId = likeBtn.dataset.wohnungid;
        // Sendet die Wohnungs-ID per POST-Request an den Server
        fetch("index.php", {
            method: "POST",
            // JSON-Daten werden gesendet
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ wohnungId: wohnungId }),
        })
            .then(response => {
                // Wenn der Server erfolgreich antwortet
                if (response.ok) {
                    // Wechselt die Farbe des Buttons zwischen orange und rot
                    if (likeBtn.style.color === "orange") {
                        likeBtn.style.color = "red";
                    } else {
                        likeBtn.style.color = "orange";
                    }
                } else {
                    throw new Error('Network response was not ok');
                }
            })

            // Fehler protokollieren
            .catch(error => {
                console.error('Error:', error);
            }
            )
    }
}
function scrollImageStrip(event) {
    event.preventDefault();
    //Den Context bestimmen(current Wohnungcard)
    let appartmentCard = event.target.closest('.appartment-card');
    //Anzahl der ausgefürte Scrolls für bestimmte Appartment-Card mithilfe data-scroll  Attribute bekommen
    let countScroll = parseInt(appartmentCard.dataset.scroll);


    let countScrollHtmlEl = appartmentCard.querySelector('.count-scroll');
    let imgInnerContainer = appartmentCard.querySelector('.img-inner-container');

    //Wie viel Bilder zur diese Card gehören
    let imgQuantity = appartmentCard.querySelectorAll('img').length;

    //Bestimmt maximal scroll Anzahl (in %)
    let maxScrolls = imgQuantity * 100 - 100;


    //Wenn event auf svg order path von rechte Pfeile passiert ist
    //und wenn countScroll maximal mögliche Wert nicht erreicht hat
    if ((event.target.classList.contains('arrow-right')
        || event.target.parentElement.classList.contains('arrow-right')) && maxScrolls > countScroll) {
        countScroll = 100 + countScroll;

        //Parent element von der Bilder auf CountScroll Wert von default Position rechts bewegen
        //d.h. auf 100% von current Position
        imgInnerContainer.style.transform = "translate(-" + countScroll + "%)";
        //Wenn event auf svg order path von linke Pfeile passiert ist
    } else if (0 < countScroll && (event.target.classList.contains('arrow-left') || event.target.parentElement.classList.contains('arrow-left'))) {
        countScroll = countScroll - 100;
        //Parent element von der Bilder auf CountScroll Wert von default Position links bewegen
        //d.h. auf 100% von current Position
        imgInnerContainer.style.transform = "translate(-" + countScroll + "%)";
    }

    //Text, der gescrollte Images zeigt erneuern
    countScrollHtmlEl.innerText = (countScroll / 100) + 1 + "/" + imgQuantity;

    //Data-scroll Attribute für diese Card erneuern
    appartmentCard.dataset.scroll = countScroll.toString();

}



