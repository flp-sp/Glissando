<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-Vindo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Pacifico&family=Orbitron:wght@700&family=Playfair+Display:ital,wght@1,700&family=Lobster&family=Caveat:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
    <main>
        <svg class="gliss" viewBox="0 0 260 150" aria-hidden="true">
            <defs>
                <linearGradient id="g" x1="0" y1="1" x2="1" y2="0">
                    <stop offset="0" stop-color="#cd5b51"/>
                    <stop offset="1" stop-color="#f3a36b"/>
                </linearGradient>
            </defs>
            <path class="wave" d="M34 118 Q58 88 82 104 T132 80 T182 56 T226 34"
                  fill="none" stroke="url(#g)" stroke-width="5" stroke-linecap="round"/>
            <g fill="url(#g)">
                <ellipse class="note n1" cx="26" cy="122" rx="12" ry="8" transform="rotate(-20 26 122)"/>
                <ellipse class="note n2" cx="234" cy="30" rx="12" ry="8" transform="rotate(-20 234 30)"/>
            </g>
            <g stroke="url(#g)" stroke-width="4" stroke-linecap="round">
                <line class="note n1" x1="37" y1="118" x2="37" y2="76"/>
                <line class="note n2" x1="245" y1="26" x2="245" y2="-16"/>
            </g>
        </svg>

        <p class="sub">Bem-vindo ao</p>
        <h1 id="brand">Glissando</h1>

        <a class="btn" href="inicio.php">Iniciar</a>
    </main>

    <script>
        const fontes = [
            "'Playfair Display', serif",
            "'Pacifico', cursive",
            "'Orbitron', sans-serif",
            "'Cinzel', serif",
            "'Lobster', cursive",
            "'Caveat', cursive"
        ];
        const brand = document.getElementById('brand');
        let i = 0;

        setInterval(() => {
            brand.classList.add('fade');
            setTimeout(() => {
                i = (i + 1) % fontes.length;
                brand.style.fontFamily = fontes[i];
                brand.classList.remove('fade');
            }, 350);
        }, 1800);
    </script>
</body>
</html>