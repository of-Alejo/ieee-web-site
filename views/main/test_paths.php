<?php
echo "=== DIAGNÓSTICO DEL SERVIDOR ===\n\n";

echo "PHP Version: " . phpversion() . "\n";
echo "OS: " . php_uname() . "\n\n";

echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "__FILE__: " . __FILE__ . "\n";
echo "SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "\n\n";

echo "allow_url_fopen: " . (ini_get('allow_url_fopen') ? 'ON' : 'OFF') . "\n";
echo "cURL instalado: " . (extension_loaded('curl') ? 'SÍ' : 'NO') . "\n";
echo "finfo disponible: " . (function_exists('finfo_buffer') ? 'SÍ' : 'NO') . "\n\n";

$testDir = dirname(__FILE__) . '/build/img/TET/photos/';
echo "Carpeta de fotos: " . $testDir . "\n";
echo "Existe: " . (is_dir($testDir) ? 'SÍ' : 'NO') . "\n";
echo "Escribible: " . (is_writable($testDir) ? 'SÍ' : 'NO') . "\n";

// Intentar crear archivo de prueba
$testFile = $testDir . 'test.txt';
if (@file_put_contents($testFile, 'test')) {
    echo "Permisos de escritura: ✓ OK\n";
    @unlink($testFile);
} else {
    echo "Permisos de escritura: ✗ FALLO\n";
}
?>