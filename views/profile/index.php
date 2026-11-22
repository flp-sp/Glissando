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
    $isSubscribed = $isSubscribed ?? false;

    $profileName = $profileUser['name'] ?? '';
    $profileHandle = $profileUser['user_name'] ?? '';
    $profileSince = !empty($profileUser['datetime_created'])
        ? date('d/m/Y', strtotime($profileUser['datetime_created']))
        : '';

    // a rota/controller pode fornecer a contagem; se não fornecer, a view busca pelo id do dono do perfil
    if (!isset($subscriberCount)) {
        $subscriberCount = 0;
        if (isset($subscriptionService) && !empty($profileUser['id'])) {
            $subscriberCount = (int) $subscriptionService->getSubscriptionNumber((int) $profileUser['id']);
        }
    }

    // A rota /profile injeta $dashboardData; os padrões mantêm a view utilizável isoladamente
    $dashboardData = $dashboardData ?? [
        'totalViews'  => 0,
        'meanViews'   => 0,
        'meanLikes'   => 0,
        'subscribers' => (int) $subscriberCount,
        'topMusics'   => [],
    ];
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
    <link rel="stylesheet" href="/assets/css/dashboard.css">
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
                            <span class="profile__subscribers"> &middot; <?= $subscriberCount ?> <?= $subscriberCount === 1 ? 'inscrito' : 'inscritos' ?></span>
                        </p>
                        <?php if (!$isOwner): ?>
                            <form class="profile__subscribe-form" action="<?= $isSubscribed ? '/unsubscribe' : '/subscribe' ?>?username=<?= htmlspecialchars(urlencode($profileHandle), ENT_QUOTES, 'UTF-8') ?>" method="post">
                                <button type="submit" class="btn btn--small <?= $isSubscribed ? 'btn--subtle' : '' ?>">
                                    <?= $isSubscribed ? 'Cancelar inscrição' : 'Inscrever-se' ?>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <?php if ($isOwner): ?>
                <section class="profile__panel" aria-labelledby="panel-title">
                    <div class="profile__panel-head">
                        <p class="sub">Seu compasso</p>
                        <h3 id="panel-title">Painel do artista</h3>
                    </div>

                    <div class="dashboard">
                        <ul class="kpi-grid" aria-label="Indicadores da sua trilha">
                            <li class="kpi-card">
                                <span class="kpi-card__icon" aria-hidden="true">&#9654;</span>
                                <span class="kpi-card__label">Visualizações</span>
                                <strong class="kpi-card__value"><?= number_format($dashboardData['totalViews'], 0, ',', '.') ?></strong>
                                <span class="kpi-card__hint">acessos às suas faixas</span>
                            </li>
                            <li class="kpi-card">
                                <span class="kpi-card__icon" aria-hidden="true">&#9835;</span>
                                <span class="kpi-card__label">Média de views</span>
                                <strong class="kpi-card__value"><?= number_format($dashboardData['meanViews'], 0, ',', '.') ?></strong>
                                <span class="kpi-card__hint">por faixa enviada</span>
                            </li>
                            <li class="kpi-card">
                                <span class="kpi-card__icon" aria-hidden="true">&#9829;</span>
                                <span class="kpi-card__label">Curtidas</span>
                                <strong class="kpi-card__value"><?= number_format($dashboardData['meanLikes'], 0, ',', '.') ?></strong>
                                <span class="kpi-card__hint">por faixa enviada</span>
                            </li>
                            <li class="kpi-card">
                                <span class="kpi-card__icon" aria-hidden="true">&#9834;</span>
                                <span class="kpi-card__label">Inscritos</span>
                                <strong class="kpi-card__value"><?= number_format($dashboardData['subscribers'], 0, ',', '.') ?></strong>
                                <span class="kpi-card__hint">ouvindo sua trilha</span>
                            </li>
                        </ul>

                        <?php if ($dashboardData['topMusics']): ?>
                            <div class="dashboard__charts">
                                <section class="chart-card" aria-labelledby="chart-top-title">
                                    <p class="sub">No destaque</p>
                                    <h4 id="chart-top-title">Faixas mais ouvidas</h4>
                                    <div class="chart-card__canvas">
                                        <canvas id="chart-top-musics" role="img" aria-label="Barras com as três faixas mais ouvidas"></canvas>
                                    </div>
                                </section>

                                <section class="chart-card" aria-labelledby="chart-share-title">
                                    <p class="sub">Na mistura</p>
                                    <h4 id="chart-share-title">Participação nas views</h4>
                                    <div class="chart-card__canvas chart-card__canvas--doughnut">
                                        <canvas id="chart-views-share" role="img" aria-label="Rosca com a participação das faixas nas visualizações"></canvas>
                                    </div>
                                </section>
                            </div>
                        <?php else: ?>
                            <p class="dashboard__empty">Sua trilha ainda está sendo afinada &mdash; envie faixas para ver o gráfico.</p>
                        <?php endif; ?>
                    </div>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof Chart === 'undefined') {
                return;
            }

            const barCanvas = document.getElementById('chart-top-musics');
            const doughnutCanvas = document.getElementById('chart-views-share');
            if (!barCanvas || !doughnutCanvas) {
                return;
            }

            const topMusics = <?= json_encode($dashboardData['topMusics'], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            const totalViews = <?= (int) $dashboardData['totalViews'] ?>;

            const labels = topMusics.map((music) => String(music.name ?? 'Faixa'));
            const values = topMusics.map((music) => Number(music.views ?? 0));

            const chartColors = ['#f3a36b', '#cd5b51', '#554865'];
            const textColor = '#e9dfcc';
            const gridColor = 'rgba(233, 223, 204, 0.08)';

            new Chart(barCanvas, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: chartColors.slice(0, values.length),
                        borderRadius: 999,
                        borderSkipped: false,
                        barThickness: 18,
                    }],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (context) => ` ${context.parsed.x.toLocaleString('pt-BR')} visualizações`,
                            },
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: gridColor },
                            ticks: { color: textColor, precision: 0, font: { size: 11 } },
                        },
                        y: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 12 } },
                        },
                    },
                },
            });

            const otherViews = Math.max(0, totalViews - values.reduce((sum, value) => sum + value, 0));
            const shareLabels = [...labels];
            const shareValues = [...values];

            if (otherViews > 0) {
                shareLabels.push('Outras');
                shareValues.push(otherViews);
            }

            new Chart(doughnutCanvas, {
                type: 'doughnut',
                data: {
                    labels: shareLabels,
                    datasets: [{
                        data: shareValues,
                        backgroundColor: [...chartColors, 'rgba(233, 223, 204, 0.35)'].slice(0, shareValues.length),
                        borderColor: 'rgba(53, 38, 48, 0.9)',
                        borderWidth: 2,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '66%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: textColor, boxWidth: 10, boxHeight: 10, padding: 14, font: { size: 11 } },
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => {
                                    const sum = context.dataset.data.reduce((total, value) => total + value, 0);
                                    const percent = sum ? Math.round((context.parsed / sum) * 100) : 0;
                                    return ` ${context.label}: ${percent}%`;
                                },
                            },
                        },
                    },
                },
            });
        });
    </script>
</body>
</html>
