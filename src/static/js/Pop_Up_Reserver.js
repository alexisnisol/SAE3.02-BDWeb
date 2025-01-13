function openBookingPopup(courseInfo) {
    // Met à jour le contenu du popup
    updatePopupContent(courseInfo);

    // Charge les poneys disponibles
    fetchPoneyDispo(courseInfo.date, courseInfo.heure);

    // Affiche le popup
    togglePopupVisibility(true);
}

function updatePopupContent(courseInfo) {
    const popupTitle = document.getElementById('popup-title');
    const courseInfoElem = document.getElementById('popup-course-info');
    const dateTimeElem = document.getElementById('popup-date-time');

    popupTitle.textContent = `Réservation pour le cours : ${courseInfo.nom_cours}`;
    courseInfoElem.innerHTML = `
        <p><strong>Cours :</strong> ${courseInfo.nom_cours}</p>
        <p><strong>Moniteur :</strong> ${courseInfo.moniteur}</p>
        <p><strong>Capacité Maximale:</strong> ${courseInfo.nb_personnes_max}</p>
        <p><strong>Niveau:</strong> ${courseInfo.niveau}</p>
    `;
    dateTimeElem.textContent = `${courseInfo.date} de ${courseInfo.heure} à ${courseInfo.heureFin}`;

    document.getElementById('id_cours').value = courseInfo.id_cours;
    document.getElementById('dateC').value = `${courseInfo.date} ${courseInfo.heure}`;
}

function fetchPoneyDispo(date, heure) {
    fetch('/App/Controllers/Planning/PlanningDB.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'getPoneyDispo',
            date,
            heure
        })
    })
        .then(response => response.json())
        .then(data => handlePoneyDispoResponse(data))
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la récupération des poneys disponibles.');
        });
}

function handlePoneyDispoResponse(data) {
    if (data.success) {
        const poneys = data.poneys;
        const poneySelect = document.getElementById('poney_dispo');
        poneySelect.innerHTML = '';

        poneys.forEach(poney => {
            const option = document.createElement('option');
            option.value = poney.id;
            option.textContent = `${poney.nom} - ${poney.poids_max} kg - ${poney.age} ans`;
            poneySelect.appendChild(option);
        });
    } else {
        alert('Erreur lors du chargement des poneys disponibles.');
    }
}

function togglePopupVisibility(isVisible) {
    const popup = document.getElementById('booking-popup');
    popup.style.display = isVisible ? 'flex' : 'none';
}

function closeBookingPopup() {
    togglePopupVisibility(false);
}

document.getElementById('booking-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const id_user = document.getElementById('id_user').value;
    const id_cours = document.getElementById('id_cours').value;
    const id_poney = document.getElementById('poney_dispo').value;
    const date = document.getElementById('dateC').value;

    submitBooking({ id_user, id_cours, id_poney, date });
});

function submitBooking(bookingData){
    fetch('/App/Controllers/Planning/PlanningDB.php',{
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(bookingData)
    })
        .then(response => response.json())
        .then(data => handleBookingResponse(data))
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la réservation.' + error);
        });
}

function handleBookingResponse(data) {
    if (data.success) {
        alert(data.message);
        closeBookingPopup();
    } else {
        alert('Erreur lors de la réservation: ' + data.message);
    }
}
