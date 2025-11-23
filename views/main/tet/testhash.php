<?php

require_once __DIR__ . '/../../../models/QRGenerator.php';


// El hash que quieres probar
$testHash = '1f59314f4205f8129679d5a775b8e8ce4b1d031e1d09fefd3d318004fb307b83';

// URL base
$baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test QR - Hash específico</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 500px;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
        }
        .qr-display {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .qr-display img {
            max-width: 100%;
            border: 3px solid #667eea;
            border-radius: 8px;
        }
        .hash-info {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 12px;
            word-break: break-all;
            margin: 20px 0;
            text-align: left;
        }
        .redirect-url {
            background: #fff3cd;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 13px;
            word-break: break-all;
            text-align: left;
        }
        .btn {
            display: inline-block;
            margin: 10px 5px;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #764ba2;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>🔍 Test QR - Hash Específico</h1>
    
    <div class="qr-display">
        <?php echo QRGenerator::displayQRImage($testHash, $baseURL, 300); ?>
    </div>
    
    <div class="hash-info">
        <strong>Hash:</strong><br>
        <?php echo $testHash; ?>
    </div>
    
    <div class="redirect-url">
        <strong>URL de redirección:</strong><br>
        <code><?php echo htmlspecialchars($baseURL . '/tet/ingreso?code=' . $testHash); ?></code>
    </div>
    
    <div>
        <a href="<?php echo htmlspecialchars($baseURL . '/tet/ingreso?code=' . $testHash); ?>" class="btn">
            🔗 Probar URL manualmente
        </a>
        <button class="btn btn-secondary" onclick="location.reload()">
            🔄 Refrescar
        </button>
    </div>
    
    <p style="margin-top: 30px; color: #666; font-size: 13px;">
        📱 Escanea el QR con tu smartphone o haz clic en "Probar URL manualmente"
    </p>
</div>

</body>
</html>
