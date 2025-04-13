<section class="sectRegister hide noBlur">
    <div>
        <article>
            <h2 hidden>Registrar usuario</h2>
            <div>
                <p>Regístrate y Unete a nosotros</p>
            </div>
        </article>
        <button class="btnClose" type="button" id="closeSectRegister" title="Cerrar sección">
            <i class="fa-regular fa-circle-xmark"></i><span hidden>Cerrar</span>
        </button>
    </div>

    <div class="alerts" id="alertsRegister"></div> <!-- alerts de JS -->

    <form id="formRegister" autocomplete="on">
        <div class="field">
            <label for="nameRegister">
                <span hidden>Nombre</span>
                <i class="fa-solid fa-id-card"></i>
            </label>
            <input type="text" name="nameRegister" id="nameRegister" placeholder="Tu nombre" maxlength="45" autocomplete="given-name">
        </div>
        <div class="field">
            <label for="lastNameRegister">
                <span hidden>Apellido</span>
                <i class="fa-solid fa-signature"></i>
            </label>
            <input type="text" name="lastNameRegister" id="lastNameRegister" placeholder="Tu apellido" maxlength="45" autocomplete="family-name">
        </div>
        <div class="field">
            <label for="telRegister">
                <span hidden>Número telefónico</span>
                <i class="fa-solid fa-mobile-screen-button"></i>
            </label>
            <input type="tel" name="telRegister" id="telRegister" placeholder="Tu celular">
        </div>

        <div class="field">
            <label for="emailRegister">
                <span hidden>Correo electrónico</span>
                <i class="fa-regular fa-envelope"></i>
            </label>
            <input type="email" name="emailRegister" id="emailRegister" placeholder="Tu correo" maxlength="50"
            autocomplete="username">
        </div>

        <div class="field">
            <label for="passwordRegister">
                <span hidden>Contraseña</span>
                <i class="fa-solid fa-key"></i>
            </label>
            <input type="password" name="passwordRegister" id="passwordRegister" placeholder="Crea tu contraseña" maxlength="30" autocomplete="off">
        </div>
        
        <div class="field">
            <label for="confirmPasswordRegister">
                <span hidden>Repite la contraseña</span>
                <i class="fa-solid fa-repeat"></i>
            </label>
            <input type="password" name="confirmPasswordRegister" id="confirmPasswordRegister" placeholder="Repite la contraseña" maxlength="30" autocomplete="off">
        </div>

        <input class="btnSubmit" type="submit" value="Registrar" id="btnSubmitRegister">
    </form>
</section>