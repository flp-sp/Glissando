<?php
$host = 'localhost';
$port = 8000;
$public = __DIR__ . '/public';

$command = sprintf('php -S %s:%d -t %s', $host, $port, escapeshellarg($public));

echo "\n";
echo "Iniciando servidor PHP\n";
echo "======================\n";
echo "http://{$host}:{$port}\n";
echo "Ctrl + C para parar\n\n";

passthru($command);
?>
 