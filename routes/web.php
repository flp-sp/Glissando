<?php

use App\Controllers\LikesController;
use App\Controllers\MusicController;
use App\Controllers\UserController;
use App\Controllers\SubscriptionController;

use App\Repositories\LikesRepository;
use App\Repositories\MusicRepository;
use App\Repositories\UserRepository;
use App\Repositories\SubscriptionRepository;

use App\Services\LikeService;
use App\Services\MusicService;
use App\Services\UserService;
use App\Services\SubscriptionService;

$method = $_SERVER["REQUEST_METHOD"];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$userRepository = new UserRepository($pdo);
$userService = new UserService($userRepository);
$userController = new UserController($userService);

$musicRepository = new MusicRepository($pdo);
$musicService = new MusicService($musicRepository);
$musicController = new MusicController($musicService);

$likeRepository = new LikesRepository($pdo);
$likeService = new LikeService($likeRepository);
$likeController = new LikesController($likeService);

$subscriptionRepository = new SubscriptionRepository($pdo);
$subscriptionService = new SubscriptionService($subscriptionRepository);
$subscriptionController = new SubscriptionController($subscriptionService);

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
    $userController->login();
}
elseif ($method == 'POST'&& $uri == '/signin') {
    $userController->create();
    exit();
}

# Homepage
elseif ($method == 'GET'&& $uri == '/homepage') {
    require __DIR__ . '/../views/app/index.php';
}
elseif ($method == 'POST'&& $uri == '/upload') {
    $musicController->upload();
    require __DIR__ . '/../views/app/index.php';
}
elseif ($method == 'GET'&& $uri == '/player') {
    require __DIR__ . '/../views/app/player.php';
}

# Serve musics
elseif ($method == 'GET' && preg_match('#^/covers/([a-f0-9]{32}\.(jpg|png|webp))$#', $uri, $m)) {
    $musicController->serveCover($m[1]);
}
elseif ($method == 'GET' && preg_match('#^/musics/([a-f0-9]{32}\.(mp3|m4a|webm|ogg|wav|flac))$#', $uri, $m)) {
    $musicController->serveMusic($m[1]);
}

# Users page
elseif ($method == 'GET'&& $uri == '/profile') {
    $profileData = $userController->getUserInfo();
    $profileUser = [
            'id' => (int) $profileData['id'],
            'name' => $profileData['name'],
            'user_name' => $profileData['user_name'],
            'datetime_created' => $profileData['datetime_created'],
        ];
    
    $isOwner = $_SESSION['user_name'] === $profileUser['user_name'];
    $profileMusics = $musicController->getUserMusics($profileUser['user_name']);
    $subscriberCount = $subscriptionController->getSubscriptionNumber((int) $profileUser['id']);

    require __DIR__ .'/../views/profile/index.php';
}

# Methods
elseif ($method == 'POST'&& $uri == '/like') { 
    $user = $userService->searchUserByUserName($_SESSION['user_name']);

    $likeController->likeMusic((int)$user['id'], (int)$_POST['music_id']);
    require __DIR__ . '/../views/app/index.php';
}
elseif ($method == 'POST'&& $uri == '/subscribe') { 
    $user = $userService->searchUserByUserName($_SESSION['user_name']);
    $followed = $userService->searchUserByUserName($_GET['username']);

    $subscriptionController->subscribeUser((int)$followed['id'], (int)$user['id']);
    header('Location: /profile?username=' . urlencode($followed['user_name']));
}

# Swagger
elseif ($method == 'GET' && $uri == '/docs') {
    require __DIR__ . '/../docs/swagger.php';
}

elseif ($method == 'GET'&& $uri == '/openapi.yaml') {
    header('Content-Type: application/yaml; charset=utf-8');
    readfile(__DIR__ . '/../docs/openapi.yaml');
    exit;
}
?>