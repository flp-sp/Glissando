<?php
    session_start();
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
            <p class="sub">Sua sessão está no compasso</p>
            <h2>Olá, <?= $_SESSION['user_name'] ?></h2>
        </header>

        <button type="button" class="btn" id="upload-btn">Enviar música</button>

        <!-- deixe o resto da página vazia por enquanto, quero validar a função de fazer upload de músicas -->
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
