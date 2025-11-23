<main class="sync-results-wrapper">
    <h1>📊 Sincronización Google Sheets</h1>
    
    <?php if ($result['success']): ?>
        <div class="alert alert-success">
            <h2>✓ Sincronización Completada</h2>
            <ul>
                <li><strong>Insertados:</strong> <?php echo $result['inserted']; ?></li>
                <li><strong>Actualizados:</strong> <?php echo $result['updated']; ?></li>
                <li><strong>Fotos descargadas:</strong> <?php echo $result['photos']; ?></li>
                <li><strong>Errores:</strong> <?php echo $result['errors']; ?></li>
            </ul>
        </div>
        
        <?php if (!empty($result['missing_emails'])): ?>
        <div class="alert alert-danger">
            <h2>⚠️ Registros sin Foto (<?php echo count($result['missing_emails']); ?>)</h2>
            <p>Las siguientes personas pagaron la entrada TET pero NO han subido foto o tienen error en el formulario:</p>

            <div style="max-height: 300px; overflow-y: auto; background: #fff; padding: 15px; border-radius: 8px;">
                <table id="tableSync" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f5f5f5;">
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">#</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Nombre</th>
                            <th style="padding: 10px; text-align: left; border-bottom: 2px solid #ddd;">Correo Electrónico</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result['missing_emails'] as $index => $missing): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 8px;"><?php echo $index + 1; ?></td>
                            <td style="padding: 8px;"><?php echo htmlspecialchars($missing['name']); ?></td>
                            <td style="padding: 8px; font-family: monospace;">
                                <?php echo htmlspecialchars($missing['email']); ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <p style="margin-top: 15px;">
                <strong>Acción requerida:</strong> Contactar a estas personas para que suban su foto.
            </p>
        </div>
        <?php endif; ?>
        
        <div class="sync-log">
            <h3>📋 Log Detallado</h3>
            <pre><?php
                foreach ($result['log'] as $line) {
                    echo htmlspecialchars($line) . "\n";
                }
            ?></pre>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">
            <h2>✗ Error en Sincronización</h2>
            <p><?php echo htmlspecialchars($result); ?></p>
            <button id="retryBtnSync" class="btn">Volver a Intentar</button>

            <script>
            document.getElementById("retryBtnSync").addEventListener("click", function() {
                const btn = this;
                btn.disabled = true;
                btn.textContent = "Sincronizando...";
                location.reload();
            });
            </script>
        </div>
    <?php endif; ?>
    
    <a href="/tet/ingreso" class="btn">Ir a Control</a>
</main>
