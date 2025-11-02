<?php
// Variables esperadas: $content_state, $login_error, $attendee
?>

<main id="control-TET-Staff" class="tet-access-wrapper" role="main" aria-live="polite" aria-atomic="true">
    <?php if ($content_state == 'login'): ?>
        <h2>Acceso Staff</h2>
        <picture>
            <source srcset="/build/img/pages/tet/logo_tet_2.webp" type="image/webp">
            <img 
            loading="lazy" 
            width="200" 
            height="300" 
            src="/build/img/pages/tet/logo_tet_2.jpg" 
            alt="Logo del Seminario de Tecnologías Emergentes en Telecomunicaciones TET 2025" 
            class="logo-tet">
        </picture>
        
        <form method="POST" class="login-form" aria-describedby="loginHelp" role="form" novalidate>
            <input type="hidden" name="login_form" value="1">
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
            <source srcset="/build/img/pages/tet/logo_tet_2.webp" type="image/webp">
            <img 
            loading="lazy" 
            width="200" 
            height="300" 
            src="/build/img/pages/tet/logo_tet_2.jpg" 
            alt="Logo del Seminario de Tecnologías Emergentes en Telecomunicaciones TET 2025" 
            class="logo-tet">
        </picture>
        <section aria-label="QR Code Scanning">
            <p>¡Listo! Usa alguna aplicación de Escaneo QR en tu dispositivo para validar una entrada.</p>
            
            <a id="syncBD" href="/tet/sync" class="btn">Actualizar BD (Solo si hay cambios en los formularios) Toma su tiempo</a>
            <a href="/tet/attendees" class="btn">Todos los registros (Búsqueda manual)</a>
            <script>
            document.getElementById("syncBD").addEventListener("click", function() {
            const btn = this;
            btn.disabled = true;              // 🔒 Deshabilitar
            btn.textContent = "Sincronizando..."; // Feedback visual

            // Acción que quieras ejecutar (ejemplo: recargar página)
            location.reload();
            });
            </script>
        </section>
        <!-- In future, add html5-qrcode with aria-live and status notifications -->

    <?php elseif ($content_state == 'verify' && !empty($attendee)): ?>
        <h2>TET 2025 - Verificación QR</h2>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'registered'): ?>
            <div class="alert alert-success" role="alert" style="background: #c8e6c9; color: #2e7d32; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                ✓ Ingreso registrado correctamente
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger" role="alert" style="background: #ffcdd2; color: #c62828; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                ✗ Error al registrar ingreso
            </div>
        <?php endif; ?>
        
        <article class="attendee-card" role="region" aria-label="Attendee Information">
            <figure class="attendee-photo-wrap">
                <img 
                    src="<?php echo htmlspecialchars($photo_path); ?>"
                    alt="Foto de <?php echo htmlspecialchars($attendee->full_name); ?>"
                    class="attendee-photo"
                    width="220" 
                    height="280" 
                    loading="eager"
                >
                <figcaption><?php echo htmlspecialchars($attendee->full_name); ?></figcaption>
            </figure>
            
            <dl class="attendee-info-data">
                <dt>Correo:</dt>
                <dd><?php echo htmlspecialchars($attendee->email_address); ?></dd>
                <dt>Celular:</dt>
                <dd><?php echo htmlspecialchars($attendee->phone_number); ?></dd>
                <dt>Documento:</dt>
                <dd><?php echo htmlspecialchars($attendee->document_type . ': ' . $attendee->document_number); ?></dd>
                <dt>Tipo:</dt>
                <dd><?php echo htmlspecialchars($attendee->entry_type); ?></dd>
            </dl>
            
            <!-- Historial de ingresos -->
            <div class="entry-history" style="padding: 15px; background: #f5f5f5; border-radius: 4px; margin: 15px 0;">
                <h4>📊 Historial de Ingresos</h4>
                <dl >
                    <dt>Día 1:</dt>
                    <dd><?php echo $attendee->day1; ?></dd>
                    
                    <dt>Día 1 - Fiesta:</dt>
                    <dd><?php echo $attendee->day1_party; ?></dd>
                    
                    <dt>Día 2:</dt>
                    <dd><?php echo $attendee->day2; ?></dd>
                    
                    <dt>Día 2 - Fiesta:</dt>
                    <dd><?php echo $attendee->day2_party; ?></dd>
                    
                    <dt>Día 3:</dt>
                    <dd><?php echo $attendee->day3; ?></dd>
                    
                    <dt>Día 3 - Fiesta:</dt>
                    <dd><?php echo $attendee->day3_party; ?></dd>
                </dl>
            </div>
            
            <!-- Formulario para registrar ingreso -->
            <form method="POST" class="entry-form" aria-label="Registrar ingreso">
                <input type="hidden" name="register_entry" value="1">
                <input type="hidden" name="email_address" value="<?php echo htmlspecialchars($attendee->email_address); ?>">
                
                <div class="form-field" style="margin-bottom: 15px;">
                    <label for="event_day" style="display: block; font-weight: bold; margin-bottom: 5px;">Selecciona día/evento:</label>
                    <select name="event_day" id="event_day" required style="width: 100%; padding: 8px; font-size: 14px; border-radius: 4px; border: 1px solid #ccc;">
                        <option value="">-- Selecciona una opción --</option>
                        <option value="day1">📅 Día 1</option>
                        <option value="day1_party">🎉 Día 1 - Fiesta</option>
                        <option value="day2">📅 Día 2</option>
                        <option value="day2_party">🎉 Día 2 - Fiesta</option>
                        <option value="day3">📅 Día 3</option>
                        <option value="day3_party">🎉 Día 3 - Fiesta</option>
                    </select>
                </div>
                
                <button type="submit" class="btn confirm-btn" aria-label="Registrar ingreso" style="width: 100%; padding: 12px; background: #4caf50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold;">
                    ✓ Ingresó
                </button>
            </form>

        </article>
    <?php else: ?>
        <div class="alert alert-danger" role="alert">QR no válido.</div>
    <?php endif; ?>
</main>
