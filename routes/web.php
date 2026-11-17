<?php

use App\Controllers\MusicController;
use App\Controllers\UserController;
use App\Repositories\MusicRepository;
use App\Repositories\UserRepository;
use App\Services\MusicService;
use App\Services\UserService;

$method = $_SERVER["REQUEST_METHOD"];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method == 'GET' && $uri == '/') {
    require __DIR__ . '/../views/index.php';
}

# Autenticação do usuário
elseif ($method == 'GET' && $uri == '/login') {
    require __DIR__ . '/../views/auth/login.php';
}
elseif ($method == 'GET' && $uri == '/signin') {
    require __DIR__ . '/../views/auth/signin.php';
}
elseif ($method == 'POST'&& $uri == '/login') {
    $repository = new UserRepository($pdo);
    $service = new UserService($repository);
    $controller = new UserController($service);

    $controller->login();
}
elseif ($method == 'POST'&& $uri == '/signin') {
    $repository = new UserRepository($pdo);
    $service = new UserService($repository);
    $controller = new UserController($service);

    $controller->create();
    exit();
}

# Homepage
elseif ($method == 'GET'&& $uri == '/homepage') {
    require __DIR__ . '/../views/app/index.php';
}
elseif ($method == 'POST'&& $uri == '/upload') {
    $repository = new MusicRepository($pdo);
    $service = new MusicService($repository);
    $controller = new MusicController($service);
    
    $controller->upload();
    require __DIR__ . '/../views/app/index.php';
}
?>