<?php
// Variables esperadas: $attendees, $total
?>

<main id="tet-attendees-list" class="tet-attendees-wrapper">
    <section class="attendees-header">
        <h2>📋 Asistentes Registrados</h2>
        <p class="total-count">Total: <strong><?php echo $total; ?> asistentes</strong></p>
        <a id="syncBD" href="/tet/sync" class="btn">Actualizar BD (Solo si hay cambios en los formularios)</a>
        <script>
            document.getElementById("syncBD").addEventListener("click", function() {
            const btn = this;
            btn.disabled = true;              // 🔒 Deshabilitar
            btn.textContent = "Sincronizando..."; // Feedback visual

            // Acción que quieras ejecutar (ejemplo: recargar página)
            location.reload();
            });
        </script>
        <a href="/tet/ingreso" class="btn">Ir a Control</a>
    </section>
    <div class="search-container" style="margin-bottom: 30px; text-align: center;">
        <input type="text" id="attendee-search" placeholder="Buscar por nombre, correo o celular..." style="width: 100%; max-width: 400px; padding: 8px; font-size:14px;">
    </div>

    <?php if (empty($attendees)): ?>
        <div class="alert alert-info" role="alert">
            ℹ️ No hay asistentes registrados aún.
        </div>
    <?php else: ?>
        <div class="attendees-grid">
            <?php foreach ($attendees as $attendee): ?>
                <article class="attendee-card" data-email="<?php echo htmlspecialchars($attendee->email_address); ?>">
                    
                    <!-- Foto -->
                    <figure class="attendee-photo-wrapper">
                        <img 
                            src="<?php echo htmlspecialchars($attendee->photo_path); ?>"
                            alt="Foto de <?php echo htmlspecialchars($attendee->full_name); ?>"
                            class="attendee-photo"
                            width="150" 
                            height="150" 
                            loading="lazy"
                        >
                    </figure>
                    
                    <!-- Información Personal -->
                    <div class="attendee-info">
                        <h3><?php echo htmlspecialchars($attendee->full_name); ?></h3>
                        <dl>
                            <dt>📧 Correo:</dt>
                            <dd><?php echo htmlspecialchars($attendee->email_address); ?></dd>
                            
                            <dt>📱 Celular:</dt>
                            <dd><?php echo htmlspecialchars($attendee->phone_number); ?></dd>
                            
                            <dt>🆔 Documento:</dt>
                            <dd><?php echo htmlspecialchars($attendee->document_type . ': ' . $attendee->document_number); ?></dd>
                            
                            <dt>🏷️ Tipo:</dt>
                            <dd>
                                <span class="badge badge-<?php echo $attendee->entry_type; ?>">
                                    <?php 
                                        $types = array(
                                            'student' => 'Estudiante',
                                            'professional' => 'Profesional',
                                            'teacher' => 'Docente',
                                            'ieee_member' => 'Miembro IEEE'
                                        );
                                        echo isset($types[$attendee->entry_type]) ? $types[$attendee->entry_type] : $attendee->entry_type;
                                    ?>
                                </span>
                            </dd>
                        </dl>
                    </div>
                    
                    <!-- QR -->
                    <div class="attendee-qr">
                        <p class="qr-label">Código QR</p>
                        <img 
                            src="<?php echo htmlspecialchars($attendee->qr_url); ?>"
                            alt="QR Code"
                            class="qr-code"
                            width="120" 
                            height="120"
                        >
                        <p class="qr-hash"><?php echo substr($attendee->qr_hash_code, 0, 16); ?>...</p>
                    </div>
                    
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>


<script>
document.getElementById('attendee-search').addEventListener('input', function(e) {
    var filter = e.target.value.toLowerCase();
    var cards = document.querySelectorAll('.attendee-card');
    
    cards.forEach(function(card) {
        var name = card.querySelector('h3').textContent.toLowerCase();
        var email = card.getAttribute('data-email').toLowerCase();
        var phone = card.querySelector('dd:nth-of-type(2)').textContent.toLowerCase(); // Celular es segundo dd

        if (name.indexOf(filter) > -1 || email.indexOf(filter) > -1 || phone.indexOf(filter) > -1) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>