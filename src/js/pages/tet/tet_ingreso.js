/**
 * TET 2025 - Sistema de Login y Control de Ingresos
 * Solo Login + Verificación Manual (sin escaneo QR en navegador)
 * Compatible ES5 (PHP 5.3 environment)
 */

function tet_ingreso() {
    
    var alertManager = new AlertManager();
    var contentState = detectContentState();
    
    
    if (contentState === 'login') {
        initLogin(alertManager);
    } else if (contentState === 'verify') {
        initVerification();
    }
}

/**
 * Detectar el estado actual de la página
 */
function detectContentState() {
    var main = document.querySelector('#control-TET-Staff');
    if (!main) return 'unknown';
    
    if (main.querySelector('.login-form')) return 'login';
    if (main.querySelector('.attendee-card')) return 'verify';
    
    return 'scan'; // Vista por defecto (esperando escaneo)
}

/**
 * Inicializar formulario de login
 */
function initLogin(alertManager) {
    var form = document.querySelector('.login-form');
    var btnSubmit = form ? form.querySelector('button[type="submit"]') : null;
    var inputUsername = document.querySelector('#username');
    var inputPassword = document.querySelector('#password');
    
    if (!form || !btnSubmit) {
        console.error('❌ Formulario de login no encontrado');
        return;
    }
    
    // Contenedor de alertas
    var alertsContainer = form.querySelector('.alerts-container');
    if (!alertsContainer) {
        alertsContainer = document.createElement('div');
        alertsContainer.className = 'alerts-container hidden';
        alertsContainer.setAttribute('role', 'alert');
        alertsContainer.setAttribute('aria-live', 'polite');
        btnSubmit.parentElement.insertBefore(alertsContainer, btnSubmit);
    }
    
    // Evento submit del formulario
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Deshabilitar botón
        btnSubmit.disabled = true;
        btnSubmit.classList.add('btn-disabled');
        btnSubmit.textContent = 'Validando...';
        
        // Obtener datos
        var username = inputUsername.value.trim();
        var password = inputPassword.value;
        
        // Limpiar alertas previas
        alertManager.clear();
        
        // Validaciones básicas
        if (!username || username.length < 3) {
            alertManager.add('error', 'Usuario debe tener al menos 3 caracteres');
            alertManager.show(alertsContainer, 5000);
            resetButton();
            return;
        }
        
        if (!password || password.length < 6) {
            alertManager.add('error', 'Contraseña debe tener al menos 6 caracteres');
            alertManager.show(alertsContainer, 5000);
            resetButton();
            return;
        }
        
        // Preparar datos
        var formData = new FormData();
        formData.append('username', username);
        formData.append('pwd', password);
        
        // Mostrar mensaje de espera
        alertManager.add('info', 'Verificando credenciales...');
        alertManager.show(alertsContainer, -1);
        
        // Enviar petición
        fetch(window.location.href, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
        .then(function(data) {
            alertManager.clear();
            
            if (data.success) {
                alertManager.add('success', 'Acceso concedido. Redirigiendo...');
                alertManager.show(alertsContainer, 2000);
                
                setTimeout(function() {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                alertManager.add('error', data.error || 'Credenciales incorrectas');
                alertManager.show(alertsContainer, 6000);
                resetButton();
            }
        })
        .catch(function(error) {
            console.error('❌ Error en petición:', error);
            
            alertManager.clear();
            alertManager.add('error', 'Error de conexión. Intenta nuevamente.');
            alertManager.show(alertsContainer, 5000);
            resetButton();
        });
    });
    
    // Focus automático
    if (inputUsername) {
        inputUsername.focus();
    }
    
    // Helper para resetear botón
    function resetButton() {
        setTimeout(function() {
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('btn-disabled');
            btnSubmit.textContent = 'Acceder';
        }, 2000);
    }
}

/**
 * Inicializar vista de verificación (QR escaneado)
 */
function initVerification() {
    var entryForm = document.querySelector('.entry-form');
    
    if (entryForm) {
        var btnMarkEntry = entryForm.querySelector('button[type="submit"]');
        var selectEventDay = document.querySelector('#event_day');
        
        entryForm.addEventListener('submit', function(e) {
            e.preventDefault(); // ✅ PREVENIR envío por defecto
            
            // Validar que seleccionó un evento
            if (!selectEventDay.value) {
                alert('Por favor selecciona un día/evento');
                return;
            }
            
            // Deshabilitar botón para evitar doble click
            if (btnMarkEntry) {
                btnMarkEntry.disabled = true;
                btnMarkEntry.textContent = '⏳ Registrando ingreso...';
            }
            
            // ✅ AHORA SÍ enviar el formulario
            entryForm.submit();
        });
    }
}

