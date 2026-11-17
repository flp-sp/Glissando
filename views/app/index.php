<?php
    session_start();

    $musics = [];
    if (isset($pdo)) {
        $repository = new \App\Repositories\MusicRepository($pdo);
        $service = new \App\Services\MusicService($repository);
        $musics = $service->getMusicsTab((int)($_GET['pagina'] ?? 1));
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início | Glissando</title>
    <link rel="stylesheet" href="/assets/css/index.css">
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <main class="app-wrap">
        <header class="app-header">
            <div class="app-header__top">
                <div>
                    <p class="sub">Sua sessão está no compasso</p>
                    <h2>Olá, <?= $_SESSION['user_name'] ?></h2>
                </div>
                <a class="profile-link" href="/profile?username=<?= urlencode($_SESSION['user_name'] ?? '') ?>">Meu perfil <span aria-hidden="true">&rarr;</span></a>
            </div>
        </header>

        <button type="button" class="btn" id="upload-btn">Enviar música</button>

        <section class="library" aria-labelledby="library-title">
            <div class="library__head">
                <p class="sub">No repertório</p>
                <h3 id="library-title">Sua trilha sonora</h3>
            </div>

            <?php if ($musics): ?>
                <ul class="track-grid">
                    <?php foreach ($musics as $music): ?>
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
                <p class="library__empty">Ainda não há faixas na trilha. Envie a primeira.</p>
            <?php endif; ?>
        </section>
    </main>

    <dialog id="upload-modal" class="modal" aria-labelledby="upload-title">
        <div class="modal__card">
            <div class="modal__header">
                <h3 id="upload-title">Adicione uma faixa</h3>
                <button type="button" class="modal__close" data-close aria-label="Fechar">&times;</button>
            </div>

            <form action="/upload" method="POST" enctype="multipart/form-data">
                <div class="campo">
                    <label for="music-name">Nome da música</label>
                    <input type="text" id="music-name" name="name" placeholder="Ex.: Noite serena">
                </div>
                <div class="campo">
                    <label for="music-artist">Artista</label>
                    <input type="text" id="music-artist" name="artist" placeholder="Quem assina essa trilha?">
                </div>
                <div class="campo">
                    <label for="music-cover">Capa</label>
                    <!-- o controller lê $_POST['cover']; o arquivo segue em $_FILES['cover'] -->
                    <input type="file" id="music-cover" name="cover" accept="image/*">
                    <input type="hidden" id="cover-filename" name="cover">
                </div>
                <div class="campo">
                    <label for="music-file">Música</label>
                    <!-- o controller lê $_POST['music']; o arquivo segue em $_FILES['music'] -->
                    <input type="file" id="music-file" name="music" accept="audio/*">
                    <input type="hidden" id="music-filename" name="music">
                </div>
                <div class="acoes">
                    <button type="submit" class="btn">Adicionar à trilha</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        const modal = document.getElementById('upload-modal');

        document.getElementById('upload-btn').addEventListener('click', () => modal.showModal());
        modal.querySelector('[data-close]').addEventListener('click', () => modal.close());

        modal.addEventListener('click', (event) => {
            if (event.target === modal) modal.close();
        });

        const coverInput = document.getElementById('music-cover');
        const musicInput = document.getElementById('music-file');

        coverInput.addEventListener('change', () => {
            document.getElementById('cover-filename').value = coverInput.files[0]?.name ?? '';
        });

        musicInput.addEventListener('change', () => {
            document.getElementById('music-filename').value = musicInput.files[0]?.name ?? '';
        });
    </script>
</body>
</html>
