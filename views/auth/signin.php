<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar conta | Glissando</title>
    <link rel="stylesheet" href="/assets/css/index.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <main class="auth-wrap">
        <p class="sub">Componha seu primeiro acorde</p>
        <h2>Crie sua conta</h2>
        <form action="/signin" method="POST">
            <div class="campo">
                <label for="name">Nome</label>
                <input type="text" id="name" name="name">
            </div>
            <div class="campo">
                <label for="username">Usuário</label>
                <input type="text" id="username" name="username">
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="text" id="email" name="email">
            </div>
            <div class="campo">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="acoes">
                <button type="submit" class="btn">Entrar na melodia</button>
                <a class="alt-link" href="/login">Já tem conta? Volte ao compasso</a>
            </div>
        </form>
    </main>
</body>
</html>