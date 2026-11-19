<?php
    session_start();

    $page = max(1, (int)($_GET['pagina'] ?? 1));

    // normalização dos filtros na view; a validação definitiva deve ficar no backend
    $filterOrderOptions = ['datetime_uploaded', 'views', 'name', 'artist'];
    $filterDirectionOptions = ['DESC', 'ASC'];

    $rawOrder = is_string($_GET['ordem'] ?? null) ? $_GET['ordem'] : '';
    $rawDirection = is_string($_GET['direcao'] ?? null) ? strtoupper($_GET['direcao']) : '';
    $rawArtist = is_string($_GET['artista'] ?? null) ? $_GET['artista'] : 'all';
    $rawUploader = $_GET['uploader'] ?? 0;

    $filterOrder = in_array($rawOrder, $filterOrderOptions, true) ? $rawOrder : 'datetime_uploaded';
    $filterDirection = in_array($rawDirection, $filterDirectionOptions, true) ? $rawDirection : 'DESC';
    $filterArtist = is_scalar($rawArtist) ? (string) $rawArtist : 'all';
    $filterUploader = is_scalar($rawUploader) ? (int) $rawUploader : 0;

    $musics = [];
    $allArtists = [];
    $allUploaderIds = [];
    $hasNextPage = false;
    if (isset($pdo)) {
        $repository = new \App\Repositories\MusicRepository($pdo);
        $service = new \App\Services\MusicService($repository);
        $musics = $service->getMusicsTab($page, $filterOrder, $filterDirection, $filterArtist, $filterUploader);
        $allArtists = $service->getColumnData('artist');
        $allUploaderIds = $service->getColumnData('fk_user_upload');
        $hasNextPage = count($musics) === MAX_PER_PAGE;
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

            <!-- filtros: os parâmetros são normalizados na view e repassados ao service; a validação definitiva deve ficar no backend -->
            <?php
                $filterArtists = [];
                foreach ($allArtists as $artistRow) {
                    $artistName = $artistRow['artist'] ?? '';
                    if ($artistName !== '') {
                        $filterArtists[$artistName] = $artistName;
                    }
                }
                ksort($filterArtists, SORT_NATURAL | SORT_FLAG_CASE);

                $uploaderNames = [];
                foreach ($musics as $filterMusic) {
                    $uploaderNames[$filterMusic->fkUserUpload] = $filterMusic->uploaderName;
                }

                $filterUploaders = [];
                foreach ($allUploaderIds as $uploaderRow) {
                    $uploaderId = (int) ($uploaderRow['fk_user_upload'] ?? 0);
                    if ($uploaderId > 0) {
                        $filterUploaders[$uploaderId] = $uploaderNames[$uploaderId] ?? ('Usuário #' . $uploaderId);
                    }
                }
                ksort($filterUploaders, SORT_NATURAL);
                $filterOrderLabels = [
                    'datetime_uploaded' => 'Mais recentes',
                    'views' => 'Mais ouvidas',
                    'name' => 'Nome',
                    'artist' => 'Artista',
                ];
                $filterDirectionLabels = [
                    'DESC' => 'Decrescente &darr;',
                    'ASC' => 'Crescente &uarr;',
                ];
                $filterQuery = http_build_query(array_diff_key($_GET, ['pagina' => true]));
            ?>
            <form class="filters" method="get" action="/homepage" aria-label="Afinar a trilha">
                <div class="filters__field">
                    <label class="filters__label" for="filter-order">Ordenar</label>
                    <select class="filters__select" id="filter-order" name="ordem">
                        <?php foreach ($filterOrderLabels as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $filterOrder === $value ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filters__field">
                    <label class="filters__label" for="filter-direction">Direção</label>
                    <select class="filters__select" id="filter-direction" name="direcao">
                        <?php foreach ($filterDirectionLabels as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $filterDirection === $value ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filters__field">
                    <label class="filters__label" for="filter-artist">Artista</label>
                    <select class="filters__select" id="filter-artist" name="artista">
                        <option value="all" <?= $filterArtist === 'all' ? 'selected' : '' ?>>Todos os artistas</option>
                        <!-- coluna "artist" via getColumnData(); deduplicação visual aqui, DISTINCT pode ir no repository -->
                        <?php foreach ($filterArtists as $artistName): ?>
                            <option value="<?= htmlspecialchars($artistName) ?>" <?= $filterArtist === $artistName ? 'selected' : '' ?>><?= htmlspecialchars($artistName) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filters__field">
                    <label class="filters__label" for="filter-uploader">Enviado por</label>
                    <select class="filters__select" id="filter-uploader" name="uploader">
                        <option value="0" <?= $filterUploader === 0 ? 'selected' : '' ?>>Todos</option>
                        <!-- ids via getColumnData('fk_user_upload'); nomes vêm da página atual, fallback para id -->
                        <?php foreach ($filterUploaders as $filterId => $filterName): ?>
                            <option value="<?= (int) $filterId ?>" <?= $filterUploader === (int) $filterId ? 'selected' : '' ?>><?= htmlspecialchars($filterName) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="filters__actions">
                    <button type="submit" class="btn btn--small">Aplicar</button>
                    <a class="filters__reset" href="/homepage">Limpar</a>
                </div>
            </form>

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
                            <a class="track-card__uploader" href="/profile?username=<?= urlencode($music->uploaderName) ?>">por @<?= htmlspecialchars($music->uploaderName) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="library__empty">Ainda não há faixas na trilha. Envie a primeira.</p>
            <?php endif; ?>

            <?php if ($musics || $page > 1): ?>
                <nav class="pagination" aria-label="Paginação da trilha">
                    <?php
                        $previousQuery = $filterQuery ? $filterQuery . '&pagina=' . ($page - 1) : 'pagina=' . ($page - 1);
                        $nextQuery = $filterQuery ? $filterQuery . '&pagina=' . ($page + 1) : 'pagina=' . ($page + 1);
                    ?>
                    <?php if ($page > 1): ?>
                        <a class="pagination__link" href="/homepage?<?= htmlspecialchars($previousQuery) ?>">&larr; Anterior</a>
                    <?php else: ?>
                        <span class="pagination__link pagination__link--disabled" aria-disabled="true">&larr; Anterior</span>
                    <?php endif; ?>

                    <span class="pagination__current" aria-current="page">Página <?= $page ?></span>

                    <?php if ($hasNextPage): ?>
                        <a class="pagination__link" href="/homepage?<?= htmlspecialchars($nextQuery) ?>">Próxima &rarr;</a>
                    <?php else: ?>
                        <span class="pagination__link pagination__link--disabled" aria-disabled="true">Próxima &rarr;</span>
                    <?php endif; ?>
                </nav>
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
