<?php
$env = parse_ini_file(__DIR__ ."/../.env");
$host = $env['host'];
$port = $env['port'];
$dbname = $env['dbname'];
$user = $env['user'];
$password = $env['password'];

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require", $user, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "". $e->getMessage();
    http_response_code(500);
    exit('Erro ao conectar ao banco');
}
?>