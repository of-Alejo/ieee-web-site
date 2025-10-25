/**
 * Sistema de manejo de alertas para formularios
 * Compatible con PHP 5.3 y navegadores modernos
 */

class AlertManager {
    constructor() {
        this.alerts = [];
    }

    /**
     * Agregar una alerta
     * @param {string} type - Tipo: 'success', 'error', 'warning', 'info'
     * @param {string} message - Mensaje a mostrar
     */
    add(type, message) {
        this.alerts.push({ type, message });
    }

    /**
     * Limpiar todas las alertas
     */
    clear() {
        this.alerts = [];
    }

    /**
     * Verificar si existen alertas de un tipo específico
     * @param {string} type - Tipo a verificar
     * @returns {boolean}
     */
    has(type) {
        return this.alerts.some(alert => alert.type === type);
    }

    /**
     * Mostrar alertas en un contenedor
     * @param {HTMLElement} container - Contenedor donde mostrar
     * @param {number} duration - Duración en ms (-1 = infinito)
     */
    show(container, duration = 5000) {
        if (!container) return;

        // Limpiar contenedor
        container.innerHTML = '';
        container.classList.remove('hidden');

        // Crear elementos de alerta
        this.alerts.forEach(alert => {
            const alertElement = document.createElement('div');
            alertElement.className = `alert alert-${alert.type}`;
            alertElement.setAttribute('role', 'alert');
            
            // Icono según tipo
            const icons = {
                success: '✓',
                error: '✕',
                warning: '⚠',
                info: 'ℹ'
            };
            
            alertElement.innerHTML = `
                <span class="alert-icon">${icons[alert.type] || 'ℹ'}</span>
                <span class="alert-message">${alert.message}</span>
            `;
            
            container.appendChild(alertElement);
        });

        // Auto-ocultar si tiene duración
        if (duration > 0) {
            setTimeout(() => {
                container.classList.add('hidden');
                this.clear();
            }, duration);
        }
    }

    /**
     * Obtener todas las alertas
     * @returns {Array}
     */
    getAll() {
        return this.alerts;
    }
}