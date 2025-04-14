<section class="sectLogin hide noBlur">
    <div>
        <article>
            <h3 hidden>Iniciar Sesión</h3>
        </article>
        
        <?php if (getSubDomain() != '/admin'): ?>
            <button class="btnClose" type="button" id="closeSectLogin" title="Cerrar sección">
                <i class="fa-regular fa-circle-xmark" aria-hidden="true"></i><span hidden>Cerrar</span>
            </button>
        <?php endif; ?>
    </div>

    <div class="alerts" id="alertsLogin"></div>

    <form id="formLogin" autocomplete="on">
        <div class="fields">
            <?php foreach (['email', 'password'] as $field): ?>
                <div class="field">
                    <label for="<?= $field ?>Login">
                        <i class="fa-solid fa-<?= ($field === 'password') ? 'key' : 'id-card' ?>" aria-hidden="true"></i>
                    </label>
                    <input type="<?= ($field === 'email') ? 'email' : 'password' ?>"
                            name="<?= $field ?>Login"
                            id="<?= $field ?>Login"
                            autocomplete="<?= ($field === 'password') ? 'current-password' : 'email' ?>"
                            placeholder="<?= 
                                $field == "email" ?  "Correo" : "Contraseña"
                            ?>">
                </div>
            <?php endforeach; ?>
        </div>

        <input class="btnSubmit" type="submit" value="Ingresar" id="btnSubmitLogin">
    </form>

    <?php if (getSubDomain() != '/admin'): ?>
        <div>
            <button class="btnRegister" type="button" id="btnRegister">¿Aún no tienes una cuenta? Créala.</button>
            <button class="btnRecoverPassword" type="button" id="btnRecoverPassword">¿Olvidaste tu contraseña?</button>
        </div>
    <?php endif; ?>
</section>
