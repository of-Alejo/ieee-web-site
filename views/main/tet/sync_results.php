<main class="sync-results-wrapper">
    <h1>📊 Sincronización Google Sheets</h1>
    
    <?php if ($result['success']): ?>
        <div class="alert alert-success">
            <h2>✓ Sincronización Completada</h2>
            <ul>
                <li><strong>Insertados:</strong> <?php echo $result['inserted']; ?></li>
                <li><strong>Actualizados:</strong> <?php echo $result['updated']; ?></li>
                <li><strong>Errores:</strong> <?php echo $result['errors']; ?></li>
            </ul>
        </div>
        
        <div  class="sync-log">
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
            <p><?php echo htmlspecialchars($result['error']); ?></p>
            <button id="retryBtnSync" class="btn">Volver a Intentar</button>

            <script>
            document.getElementById("retryBtnSync").addEventListener("click", function() {
            const btn = this;
            btn.disabled = true;
            btn.textContent = "Sincronizando... Aprox 1.5 minutos";

            location.reload();

            setTimeout(() => {
                btn.disabled = false;           
                btn.textContent = "Volver a Intentar";
            }, 90000);
            });
            </script>

        </div>
    <?php endif; ?>
    
    <a href="/tet/ingreso" class="btn">Ir a Control</a>
</main>
