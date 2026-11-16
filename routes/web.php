<?php
$method = $_SERVER["REQUEST_METHOD"];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method == 'GET' && $uri == '/') {
    require __DIR__ . '/../views/index.php';
}
?>