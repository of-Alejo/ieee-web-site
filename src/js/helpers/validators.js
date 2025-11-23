/**
 * Funciones de validación para formularios
 */

const Validators = {

    /**
     * Validar longitud de string
     * @param {string} str
     * @param {number} min
     * @param {number} max
     * @returns {boolean}
     */
    isValidLength(str, min, max) {
        const length = str.length;
        return length >= min && length <= max && str.trim().length > 0;
    },

    /**
     * Sanitizar entrada de texto básica
     * @param {string} str
     * @returns {string}
     */
    sanitize(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return DOMPurify.sanitize(div.innerHTML.trim());
    },

    /**
     * Validar caracteres permitidos en username
     * @param {string} username
     * @returns {boolean}
     */
    isValidUsername(username) {
        const usernameRegex = /^[a-zA-Z0-9_-]+$/;
        return usernameRegex.test(username);
    }
};