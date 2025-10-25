/**
 * TET 2025 - Sistema de Login y Validación de Ingresos
 * Módulo para autenticación de staff y escaneo de QR
 * Compatible ES5 (PHP 5.3 environment)
 */

function tet_ingreso() {
    console.log('🎫 Módulo TET Ingreso iniciado');
    
    var alertManager = new AlertManager();
    var contentState = detectContentState();
    
    console.log('📍 Estado detectado:', contentState);
    
    switch(contentState) {
        case 'login':
            initLogin(alertManager);
            break;
        case 'scan':
            initScanner(alertManager);
            break;
        case 'verify':
            initVerification(alertManager);
            break;
        default:
            console.warn('⚠️ Estado desconocido');
    }
}

/**
 * Detectar el estado actual de la página
 * @returns {string} Estado: 'login', 'scan', 'verify'
 */
function detectContentState() {
    var main = document.querySelector('#control-TET-Staff');
    if (!main) return 'unknown';
    
    if (main.querySelector('.login-form')) return 'login';
    if (main.querySelector('section[aria-label="QR Code Scanning"]')) return 'scan';
    if (main.querySelector('.attendee-card')) return 'verify';
    
    return 'unknown';
}

/**
 * Inicializar formulario de login
 * @param {AlertManager} alertManager
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
    
    console.log('✓ Formulario de login detectado');
    
    // Verificar que DOMPurify esté cargado
    if (typeof DOMPurify === 'undefined') {
        console.error('❌ DOMPurify no está cargado');
        alertManager.add('error', 'Error de seguridad: librería de sanitización no cargada');
        
        var alertsContainer = document.createElement('div');
        alertsContainer.className = 'alerts-container';
        btnSubmit.parentElement.insertBefore(alertsContainer, btnSubmit);
        alertManager.show(alertsContainer, -1);
        
        btnSubmit.disabled = true;
        return;
    }
    
    console.log('✓ DOMPurify cargado, versión:', DOMPurify.version);
    
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
        
        console.log('📝 Formulario enviado');
        
        // Deshabilitar botón
        btnSubmit.disabled = true;
        btnSubmit.classList.add('btn-disabled');
        btnSubmit.textContent = 'Validando...';
        
        // Obtener y sanitizar datos
        var username = Validators.sanitize(inputUsername.value);
        var password = inputPassword.value; // NO sanitizar password
        
        console.log('🔒 Username sanitizado:', username);
        
        // Limpiar alertas previas
        alertManager.clear();
        
        // Validaciones locales
        if (!Validators.isValidUsername(username)) {
            alertManager.add('error', 'Usuario contiene caracteres no permitidos');
        }
        
        if (!Validators.isValidLength(username, 3, 50)) {
            alertManager.add('error', 'Usuario debe tener entre 3 y 50 caracteres');
        }
        
        if (!Validators.isValidLength(password, 6, 100)) {
            alertManager.add('error', 'Contraseña debe tener entre 6 y 100 caracteres');
        }
        
        // Si hay errores locales
        if (alertManager.has('error')) {
            alertManager.show(alertsContainer, 5000);
            
            setTimeout(function() {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('btn-disabled');
                btnSubmit.textContent = 'Acceder';
            }, 3000);
            
            return;
        }
        
        // Preparar datos para enviar
        var formData = new FormData();
        formData.append('username', username);
        formData.append('pwd', password);
        
        // Mostrar mensaje de espera
        alertManager.add('info', 'Verificando credenciales...');
        alertManager.show(alertsContainer, -1);
        
        // Enviar petición - RECIBIR JSON
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
                // Login exitoso
                alertManager.add('success', '✓ Acceso concedido. Redirigiendo...');
                alertManager.show(alertsContainer, 2000);
                
                setTimeout(function() {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                // Error del servidor
                alertManager.add('error', data.error || 'Credenciales incorrectas');
                alertManager.show(alertsContainer, 6000);
                
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('btn-disabled');
                btnSubmit.textContent = 'Acceder';
            }
        })
        .catch(function(error) {
            console.error('❌ Error en petición:', error);
            
            alertManager.clear();
            alertManager.add('error', 'Error de conexión. Intenta nuevamente.');
            alertManager.show(alertsContainer, 5000);
            
            btnSubmit.disabled = false;
            btnSubmit.classList.remove('btn-disabled');
            btnSubmit.textContent = 'Acceder';
        });
    });
    
    // Focus automático
    if (inputUsername) {
        inputUsername.focus();
    }
}

/**
 * Inicializar vista de verificación
 * @param {AlertManager} alertManager
 */
function initVerification(alertManager) {
    console.log('✓ Vista de verificación cargada');
    
    var entryForm = document.querySelector('.entry-form');
    
    if (entryForm) {
        var btnMarkEntry = entryForm.querySelector('button[type="submit"]');
        
        entryForm.addEventListener('submit', function(e) {
            // Deshabilitar botón para evitar doble click
            if (btnMarkEntry) {
                btnMarkEntry.disabled = true;
                btnMarkEntry.textContent = 'Registrando...';
            }
            
            // El formulario se enviará normalmente (POST)
            // El servidor procesará mark_as_in y qr_hash_code
        });
    }
    
    // Efecto visual para estado "ya ingresó"
    var statusAttended = document.querySelector('.status-badge.attended');
    if (statusAttended) {
        statusAttended.style.animation = 'pulse 2s ease-in-out';
    }
    
    // Mensaje adicional si ya ingresó
    var alertSuccess = document.querySelector('.alert-success');
    if (alertSuccess) {
        console.log('ℹ️ Asistente ya registró su ingreso previamente');
    }
}
