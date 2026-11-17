<?php
    $name = $_GET['name'] ?? 'Faixa sem nome';
    $artist = $_GET['artist'] ?? 'Artista desconhecido';
    $cover = $_GET['cover'] ?? '';
    $music = $_GET['music'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($name) ?> | Glissando</title>
    <link rel="stylesheet" href="/assets/css/index.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/player.css">
</head>
<body>
    <main class="app-wrap player-wrap">
        <header class="player__head">
            <a class="player__back" href="/homepage">&larr; Voltar ao acervo</a>
            <p class="sub">Tocando agora</p>
        </header>

        <div class="player__art">
            <?php if ($cover !== ''): ?>
                <img src="/covers/<?= htmlspecialchars($cover) ?>" alt="Capa de <?= htmlspecialchars($name) ?>">
            <?php else: ?>
                <span class="player__note" aria-hidden="true">♪</span>
            <?php endif; ?>
        </div>

        <div class="player__meta">
            <h2><?= htmlspecialchars($name) ?></h2>
            <p><?= htmlspecialchars($artist) ?></p>
        </div>

        <?php if ($music !== ''): ?>
            <audio class="player__audio" controls preload="metadata">
                <source src="/musics/<?= htmlspecialchars($music) ?>">
                Seu navegador não suporta áudio.
            </audio>
        <?php else: ?>
            <p class="player__empty">Áudio indisponível para esta faixa.</p>
        <?php endif; ?>
    </main>
</body>
</html>
