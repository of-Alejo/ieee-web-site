<?php
// Variables esperadas: $content_state, $login_error, $attendee
?>

<main id="control-TET-Staff" class="tet-access-wrapper" role="main" aria-live="polite" aria-atomic="true">
    <?php if ($content_state == 'login'): ?>
        <h2>Acceso Staff</h2>
        <picture>
            <source srcset="/build/img/pages/tet/logo_tet.webp" type="image/webp">
            <img 
            loading="lazy" 
            width="200" 
            height="300" 
            src="/build/img/pages/tet/logo_tet.png" 
            alt="Logo del Seminario de Tecnologías Emergentes en Telecomunicaciones TET 2025" 
            class="logo-tet">
        </picture>
        <form method="POST" class="login-form" aria-describedby="loginHelp" role="form" novalidate>
            <div class="field">
                <label for="username">Usuario</label>
                <input type="text" name="username" id="username" required autocomplete="username" aria-required="true" aria-describedby="usernameHelp">
                <small id="usernameHelp" class="sr-only">Nombre de usuario asignado</small>
            </div>
            <div class="field">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" required autocomplete="current-password" aria-required="true" aria-describedby="passwordHelp">
                <small id="passwordHelp" class="sr-only">Pon tu contraseña</small>
            </div>
            <?php if (!empty($login_error)): ?>
                <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($login_error); ?></div>
            <?php endif; ?>
            <button type="submit" class="btn main-btn">Acceder</button>
        </form>

    <?php elseif ($content_state == 'scan'): ?>
        <h2>TET 2025 Control de Entrada QR</h2>
        <picture>
            <source srcset="/build/img/pages/tet/logo_tet.webp" type="image/webp">
            <img 
            loading="lazy" 
            width="200" 
            height="300" 
            src="/build/img/pages/tet/logo_tet.png" 
            alt="Logo del Seminario de Tecnologías Emergentes en Telecomunicaciones TET 2025" 
            class="logo-tet">
        </picture>
        <section aria-label="QR Code Scanning">
            <p>¡Listo! Usa alguna aplicación de Escaneo QR en tu dispositivo para validar una entrada.</p>
        </section>
        <!-- In future, add html5-qrcode with aria-live and status notifications -->

    <?php elseif ($content_state == 'verify' && !empty($attendee)): ?>
        <h2>TET 2025 - Verificación QR</h2>
        <article class="attendee-card" role="region" aria-label="Attendee Information">
            <figure class="attendee-photo-wrap">
                <img 
                    src="<?php echo htmlspecialchars($attendee->photo_file_path); ?>"
                    alt="Photo of <?php echo htmlspecialchars($attendee->full_name); ?>"
                    class="attendee-photo"
                    width="220" height="280" loading="eager"
                >
                <figcaption><?php echo htmlspecialchars($attendee->full_name); ?>'s Photo</figcaption>
            </figure>
            <dl class="attendee-info-data">
                <dt>Correo:</dt>
                <dd><?php echo htmlspecialchars($attendee->email_address); ?></dd>
                <dt>Celular:</dt>
                <dd><?php echo htmlspecialchars($attendee->phone_number); ?></dd>
                <dt>Tipo:</dt>
                <dd><?php echo htmlspecialchars($attendee->entry_type); ?></dd>
                <dt>Estado:</dt>
                <dd>
                    <?php if ($attendee->attended_event): ?>
                        <span class="status-badge attended" aria-live="polite">
                            Already entered on <?php echo htmlspecialchars($attendee->entry_timestamp); ?>
                        </span>
                    <?php else: ?>
                        <span class="status-badge not-in" aria-live="polite">Not registered yet</span>
                    <?php endif; ?>
                </dd>
            </dl>
            <?php if (!$attendee->attended_event): ?>
                <form method="POST" class="entry-form" aria-label="Mark entry form">
                    <input type="hidden" name="mark_as_in" value="1">
                    <input type="hidden" name="qr_hash_code" value="<?php echo htmlspecialchars($attendee->qr_hash_code); ?>">
                    <button type="submit" class="btn confirm-btn" aria-label="Mark this attendee as entered">Mark Entry</button>
                </form>
            <?php else: ?>
                <div class="alert alert-success" role="alert">This person has already entered the event.</div>
            <?php endif; ?>
        </article>
    <?php else: ?>
        <div class="alert alert-danger" role="alert">QR no válido.</div>
    <?php endif; ?>
</main>
