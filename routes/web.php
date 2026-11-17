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
elseif ($method == 'GET'&& $uri == '/player') {
    require __DIR__ . '/../views/app/player.php';
}

# Serve musics
elseif ($method == 'GET' && preg_match('#^/covers/([a-f0-9]{32}\.(jpg|png|webp))$#', $uri, $m)) {
    $repository = new MusicRepository($pdo);
    $service = new MusicService($repository);
    $controller = new MusicController($service);
    
    $controller->serveCover($m[1]);
}
elseif ($method == 'GET' && preg_match('#^/musics/([a-f0-9]{32}\.(mp3|m4a|webm|ogg|wav|flac))$#', $uri, $m)) {
    $repository = new MusicRepository($pdo);
    $service = new MusicService($repository);
    $controller = new MusicController($service);

    $controller->serveMusic($m[1]);
}

# Users page
elseif ($method == 'GET'&& $uri == '/profile') {
    $repository = new UserRepository($pdo);
    $service = new UserService($repository);
    $controller = new UserController($service);

    $profileData = $controller->getUserInfo();
    $profileUser = [
            'id' => (int) $profileData['id'],
            'name' => $profileData['name'],
            'user_name' => $profileData['user_name'],
            'datetime_created' => $profileData['datetime_created'],
        ];
    
    $isOwner = true;
    $profileMusics = [];

    require __DIR__ .'/../views/profile/index.php';
}
?>