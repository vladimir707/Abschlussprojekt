document.addEventListener("DOMContentLoaded", () => {
    const anzeigeContainer = document.getElementById("anzeigen-container");
    const formContainer = document.getElementById("anzeige-form-container");
    const form = document.getElementById("anzeige-form");
    const abbrechenButton = document.getElementById("abbrechen");
   

    // Anzeigen laden
    fetch("Meine_anzeige.php")
        .then(response => response.json())
        .then(anzeigen => {
            anzeigen.forEach(anzeige => {
                const div = document.createElement("div");
                div.className = "anzeige";

            // Преобразуем BildLinks в массив
            const bildLinksArray = anzeige.BildLinks.split(',');

            // Создаём слайдер для изображений
            const bilderHtml = bildLinksArray.map(
                bild => `<div class="swiper-slide"><img onclick="bildbearbeiten(${anzeige.WohnungId})" id=bilden src="${bild}" alt="Bild"></div>`
            ).join("");

                div.innerHTML = `
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        ${bilderHtml}
                    </div>
                    <!-- Добавляем элементы управления -->
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
                    <h3>${anzeige.Titel}</h3>
                    <p>${anzeige.Beschreibung}</p>
                    <p>${anzeige.Stadt}, ${anzeige.Postleitzahl}</p>
                    <button onclick="bearbeiten(${anzeige.WohnungId})">Bearbeiten</button>

                <button onclick="loeschen(${anzeige.WohnungId})">Löschen</button>
                `;
                anzeigeContainer.appendChild(div);

            // Инициализируем Swiper для этого контейнера
            new Swiper(".swiper-container", {
                loop: true,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
            });
            });
        });

    // Neue Anzeige hinzufügen
    document.getElementById("neue-anzeige").addEventListener("click", () => {
        formContainer.classList.remove("hidden");
        form.reset();
       // document.getElementById("wohnungId").value = "";



       
    });

    // Formular abschicken
   



form.addEventListener("submit", (e) => {
    e.preventDefault();  

   
    const formData1 = new FormData(form); // create FormData from form
    console.log("Form Data:", formData1);  // Protokollierung der FormData
    
    fetch("hizufugen_bearbeiten.php", {
        method: "POST",
        body: formData1, // Übergeben der Daten im FormData-Format 
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => { throw new Error(text) });
        }
        return response.json();
    })
    .then(data => {
        console.log("Server Response:", data); // Protokollierung der Serverantwort
        location.reload(); // Die Seite neu laden
    })
    .catch(error => {
        console.error("Error:", error); // Protokollierung der Fehler
    });
});





    
    abbrechenButton.addEventListener("click", () => {
        formContainer.classList.add("hidden");
        form.reset();
      //  location.reload(true);
    });
});


function bearbeiten(id) {
    fetch(`Meine_anzeige.php?id=${id}`)
    .then(response => response.json())
    .then(anzeige => {
        console.log("Datenempfang:", anzeige);

        Object.keys(anzeige).forEach(key => {
            const input = document.getElementById(key);

                

                // Если это поле ввода, заполняем его
            if (input) input.value = anzeige[key];
        });
                    
            
            document.getElementById("anzeige-form-container").classList.remove("hidden");
              document.getElementById("file").classList.add("hidden"); 
        })
        .catch(error => {
            console.error("Fehler beim Datenempfang:", error);
        });
}

function bildbearbeiten(id) {
    fetch(`Meine_anzeige.php?id=${id}`)
        .then(response => response.json())
        .then(anzeige => {
            console.log("Datenempfang:", anzeige);

            Object.keys(anzeige).forEach(key => {
               

                if (key === "BildLinks") {
                    // Очистка предыдущего отображения изображений
                    const previewContainer = document.getElementById("BildLink-preview-container");
                    if (previewContainer) {
                        previewContainer.innerHTML = ""; // Удалить старые изображения
                    } else {
                        // Если контейнера нет, создаём его
                        const newContainer = document.createElement("div");
                        newContainer.id = "BildLink-preview-container";
                        document.getElementById("anzeige-form").appendChild(newContainer);
                    }

                    // Разделение BildLinks на массив
                    const bildLinks = anzeige[key].split(",");

                    // Создаём элементы <img> для каждого изображения
                    bildLinks.forEach(link => {
                        const imgContainer = document.createElement("div");
                        imgContainer.style.display = "inline-block";
                        imgContainer.style.marginRight = "10px";
                        imgContainer.style.textAlign = "center";

                        const img = document.createElement("img");
                        img.src = link.trim();
                        img.alt = "Bild Vorschau";
                        img.style.width = "100px";
                        imgContainer.appendChild(img);

                        // Кнопка удаления
                        const deleteButton = document.createElement("button");
                        deleteButton.innerText = "Löschen";
                        deleteButton.style.display = "block";
                        deleteButton.style.marginTop = "5px";
                        deleteButton.style.marginBottom = "5px";
                        deleteButton.onclick = (e) => {

                            if (confirm("Möchten Sie dieses Bild wirklich löschen?")) {
                                e.preventDefault();
                                deleteImage(link.trim(), id, imgContainer);
                            }
                        };
                        
                        imgContainer.appendChild(deleteButton);
                        document.getElementById("BildLink-preview-container").appendChild(imgContainer);
                       

                       
                       
                      

                    });
                }

                // Если это поле ввода, заполняем его
               
            });
                    const addImage = document.createElement("div");
                        addImage.id = "addImage";
                        addImage.innerHTML = ` <label for="BildLinks">Bilder hochladen</label>
                        <input type="file" name="BildLinks[]" id="BildLinks" multiple>
                       
                        <button type="button" onclick="addImage(${anzeige.WohnungId})">Bild hinzufügen</button>`;
                        
                        document.getElementById("anzeige-form").appendChild(addImage); 

                        const closse = document.createElement("button");
                        closse.innerText = "Schließen";
                        closse.style.display = "block";
                        closse.style.marginTop = "5px";
                        closse.style.marginBottom = "5px";
                        closse.onclick = () => {
                          

                            document.getElementById("anzeige-form-container").classList.add("hidden");
                            document.getElementById("anzeige-form-container").reset();
                        };
                        document.getElementById("anzeige-form").appendChild(closse);
            // Показываем форму
            


        document.getElementById("anzeige-form-container").classList.remove("hidden");
              document.getElementById("form").classList.add("hidden"); 
    })
    .catch(error => {
        console.error("Fehler beim Datenempfang:", error);
    });
}





function addImage(wohnungId) {
    const fileInput = document.getElementById("BildLinks");
    const files = fileInput.files;

    if (files.length === 0) {
        alert("Bitte wählen Sie mindestens ein Bild aus.");
        return;
    }

    const formData = new FormData();

    // Добавляем идентификатор квартиры
    formData.append("wohnungId", wohnungId);

    // Добавляем файлы в FormData
    for (let i = 0; i < files.length; i++) {
        formData.append("images[]", files[i]);
    }

    fetch("upload_images.php", {
        method: "POST",
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Bilder erfolgreich hochgeladen!");
                // Здесь можно обновить список изображений в форме
                console.log("Uploaded files:", data.files);
            } else {
                alert("Fehler beim Hochladen der Bilder.");
            }
        })
        .catch(error => {
            console.error("Fehler bei der Anfrage:", error);
        });
}







function deleteImage(link, wohnungId, imgContainer) {
    fetch("delete_image.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ link, wohnungId })
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Удалить изображение из интерфейса
                imgContainer.remove();
                
            } else {
                alert("Fehler beim Löschen des Bildes: " + result.error);
            }
        })
        .catch(error => {
            console.error("Fehler beim Löschen des Bildes:", error);
        });
}



function loeschen(id) {
    if (confirm("Möchten Sie die Anzeige wirklich löschen?")) {
        fetch(`anzeige_leoschen.php`, {
            method: "POST", // benutzen wir die POST-Methode
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                action: "delete", // Aktion: Löschen
                id: id
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text); });
            }
            return response.json();
        })
        .then(data => {
            console.log("Server Response:", data);
            if (data.success) {
                // Löschen der  Anzeige aus dem DOM (Document Object Model) 
                const deletedElement = document.querySelector(`[onclick="loeschen(${id})"]`).closest(".anzeige");
                if (deletedElement) {
                    deletedElement.remove();
                }
            } else {
                alert("Die Anzeige konnte nicht gelöscht werden.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Ein Fehler ist aufgetreten. Die Anzeige wurde nicht gelöscht.");
        });
    }
}