<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | Glissando</title>
    <link rel="stylesheet" href="/assets/css/index.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <main class="auth-wrap">
        <p class="sub">Afine sua entrada</p>
        <h2>Inicie sua sessão</h2>
        <form action="/login" method="POST"><!-- metodo post -->
            <div class="campo">
                <label for="username">Usuário</label>
                <input type="text" id="username" name="username">
            </div>
            <div class="campo">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="acoes">
                <button type="submit" class="btn">Entrar no compasso</button>
                <a class="alt-link" href="/signin">Ainda sem conta? Crie sua trilha</a>
            </div>
        </form>
    </main>
</body>
</html>