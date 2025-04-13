// Variable global que almacena alerts para luego mostrarlas y borrarlas
let alerts = [];

// Esta función crea una alert de tipo HTML, ya sea de error o éxito
function createAlert(type, message = '') {
    if (type === 'clear') {
        alerts = [];
        return type;
    }
    alerts.push({ type, message });
    return type;
}

// Comprueba si existen alerts de dicho tipo
function hasAlerts(searchedType) {
    return alerts.some(alert => alert.type === searchedType);
}

// Muestra las alerts en una ubicación del DOM y por un tiempo. Si el tiempo es -1, es infinito
function showAlerts(alertsContainer, initialTime) {
    removeAlerts(); // Remueve alerts antiguas

    let time = initialTime;
    const alertDiv = document.createElement('DIV');
    const alertMessage = document.createElement('P');
    
    alertDiv.classList.add('alert');
    alertDiv.classList.add(alerts[0].type); // Agrega el tipo de alert (error, éxito, info, etc.)
    alertMessage.textContent = alerts[0].message; // Muestra el mensaje de alert
    
    alertDiv.appendChild(alertMessage);

    // Añadir ícono de carga si la alert es de tipo 'info'
    if (alerts[0].type === 'info') {
        const loadingIcon = document.createElement('i');
        loadingIcon.classList.add('fa-solid', 'fa-spinner', 'fa-spin');
        alertDiv.appendChild(loadingIcon);
    }
    
    // Añade la alert al contenedor
    alertsContainer.appendChild(alertDiv);

    // Si el tiempo es mayor o igual a 0, elimina la alert después del tiempo especificado
    if (initialTime >= 0) {
        setTimeout(() => {
            alertDiv.remove();
        }, 1000 + time);
        time += 1000;
    }
}

// Remueve todas las alerts del DOM
function removeAlerts() {
    const oldAlerts = document.querySelectorAll('.alert');
    oldAlerts.forEach(alert => alert.remove());
}
