<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Enquanto a rota não for ligada ao controller, a view usa o mínimo da sessão.
    $profileProvided = array_key_exists('profileUser', get_defined_vars());

    if (!$profileProvided && !empty($_SESSION['user_name'])) {
        $profileUser = [
            'name' => $_SESSION['user_name'],
            'user_name' => $_SESSION['user_name'],
            'datetime_created' => null,
        ];
        $isOwner = true;
    }

    $profileUser = $profileUser ?? null;
    $isOwner = $isOwner ?? false;
    $profileMusics = $profileMusics ?? [];

    $profileName = $profileUser['name'] ?? '';
    $profileHandle = $profileUser['user_name'] ?? '';
    $profileSince = !empty($profileUser['datetime_created'])
        ? date('d/m/Y', strtotime($profileUser['datetime_created']))
        : '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil | Glissando</title>
    <link rel="stylesheet" href="/assets/css/index.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/profile.css">
</head>
<body>
    <main class="app-wrap profile-wrap">
        <?php if ($profileUser): ?>
            <header class="profile__head">
                <a class="profile__back" href="/homepage">&larr; Voltar ao acervo</a>

                <div class="profile__identity">
                    <span class="profile__avatar" aria-hidden="true">♪</span>
                    <div class="profile__title">
                        <p class="sub"><?= $isOwner ? 'Sua batida' : 'No palco' ?></p>
                        <h2><?= htmlspecialchars($profileName) ?></h2>
                        <p class="profile__handle">
                            @<?= htmlspecialchars($profileHandle) ?>
                            <?php if ($profileSince): ?>
                                <span class="profile__since"> &middot; na cena desde <?= htmlspecialchars($profileSince) ?></span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </header>

            <?php if ($isOwner): ?>
                <section class="profile__panel" aria-labelledby="panel-title">
                    <div class="profile__panel-head">
                        <p class="sub">Seu compasso</p>
                        <h3 id="panel-title">Painel do artista</h3>
                    </div>

                    <div class="profile__chart" role="img" aria-label="Espaço reservado para o gráfico de atividade">
                        <span class="profile__chart-note" aria-hidden="true">♬</span>
                        <p>O gráfico entra em breve &mdash; estamos afinando essa batida.</p>
                    </div>

                    <p class="profile__hint">Enquanto isso, sua trilha continua no acervo.</p>
                </section>
            <?php endif; ?>

            <section class="profile__setlist" aria-labelledby="setlist-title">
                <div class="profile__setlist-head">
                    <p class="sub">No repertório</p>
                    <h3 id="setlist-title"><?= $isOwner ? 'Sua setlist' : 'A setlist de ' . htmlspecialchars($profileName) ?></h3>
                </div>

                <?php if ($profileMusics): ?>
                    <ul class="track-grid">
                        <?php foreach ($profileMusics as $music): ?>
                            <?php
                                $playerQuery = http_build_query([
                                    'id' => $music->id,
                                    'name' => $music->name,
                                    'artist' => $music->artist,
                                    'cover' => $music->coverPath ?? '',
                                    'music' => $music->musicPath,
                                ]);
                            ?>
                            <li>
                                <a class="track-card" href="/player?<?= htmlspecialchars($playerQuery) ?>">
                                    <span class="track-card__cover">
                                        <?php if ($music->coverPath): ?>
                                            <img src="/covers/<?= htmlspecialchars($music->coverPath) ?>" alt="Capa de <?= htmlspecialchars($music->name) ?>" loading="lazy">
                                        <?php else: ?>
                                            <span class="track-card__note" aria-hidden="true">♪</span>
                                        <?php endif; ?>
                                    </span>
                                    <span class="track-card__info">
                                        <strong class="track-card__name"><?= htmlspecialchars($music->name) ?></strong>
                                        <span class="track-card__artist"><?= htmlspecialchars($music->artist) ?></span>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="profile__empty">
                        <?= $isOwner
                            ? 'Sua setlist ainda está sendo afinada. Envie a primeira faixa no acervo.'
                            : 'A setlist deste artista ainda está sendo afinada.' ?>
                    </p>
                <?php endif; ?>
            </section>
        <?php else: ?>
            <section class="profile__notfound">
                <p class="sub">Fora do compasso</p>
                <h2>Este perfil não está na trilha</h2>
                <p>Não encontramos nenhum artista com esse nome.</p>
                <a class="btn" href="/homepage">Voltar ao acervo</a>
            </section>
        <?php endif; ?>
    </main>
</body>
</html>
