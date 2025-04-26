function menuBurger() {
    const burgerMenu = document.getElementById("burgerMenu");
    const nav = document.querySelector(".header nav");
    const body = document.body; // Seleccionamos el body para manipular el scroll

    if (burgerMenu && nav) {
        burgerMenu.addEventListener("click", () => {
            nav.classList.toggle("hide");

            // Deshabilitar o habilitar el scroll según la clase 'hide'
            if (!nav.classList.contains("hide")) {
                body.style.overflow = "hidden"; // Deshabilitar scroll
            } else {
                body.style.overflow = ""; // Restaurar scroll
            }
        });
    }
}