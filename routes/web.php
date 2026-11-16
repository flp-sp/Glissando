<?php

use App\Controllers\UserController;
use App\Repositories\UserRepository;
use App\Services\UserService;

$method = $_SERVER["REQUEST_METHOD"];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method == 'GET' && $uri == '/') {
    require __DIR__ . '/../views/index.php';
}
elseif ($method == 'GET' && $uri == '/login') {
    require __DIR__ . '/../views/auth/login.php';
}
elseif ($method == 'GET' && $uri == '/signin') {
    require __DIR__ . '/../views/auth/signin.php';
}
elseif ($method == 'POST'&& $uri == '/login') {

}
elseif ($method == 'POST'&& $uri == '/signin') {
    $repository = new UserRepository($pdo);
    $service = new UserService($repository);
    $controller = new UserController($service);

    $controller->create();
    exit();
}
?>