# AGENTS.md — Glissando

## Papel e escopo
- Atuar **apenas no front-end/visual** do Glissando.
- **Escopo permitido:** alterações visuais em `views/` (templates/markup) e em estilos dedicados em `public/assets/css/`, quando previamente delimitado pelo usuário.
- **Não alterar a lógica por trás do código:** nada de regras de negócio, controllers, services, repositories, models, rotas, configuração, banco de dados, autenticação, validações de backend ou fluxo de dados.
- Mexer **somente no visual e em elementos previamente delimitados pelo usuário** para cada tarefa. Se houver dúvida sobre o que está delimitado, perguntar antes de editar.
- Se atente ao comentários `html` dos arquivos que for editar, geralmente vão ser notas para o agente.

## Estilo visual (manter o tom atual)
- Preservar o estilo **simples, limpo e coerente** já presente no projeto.
- Respeitar a identidade visual existente (ex.: paleta `--c1` a `--c5`, gradientes suaves, cantos arredondados, tipografia legível, animações leves) e evitar exageros visuais.
- **Todo estilo deve ficar em arquivo CSS separado** (ex.: em `public/assets/css/`), referenciado pelas views; evitar estilos inline em blocos `<style>` ou atributos `style`, salvo exceção explicitamente delimitada.
- Priorizar consistência entre páginas, boa leitura, espaçamento equilibrado e responsividade básica.
- Não introduzir frameworks/bibliotecas visuais ou mudanças estruturais amplas sem pedido explícito.

## Tom e referências musicais
- Sempre que possível, trazer **referências a músicas, ritmos, instrumentos, termos musicais ou metáforas sonoras** de forma sutil e contextual (nomes, textos de apoio, microcopy, classes/comentários visuais quando fizer sentido), sem comprometer clareza ou usabilidade.
- Manter o espírito do nome **Glissando**: fluidez, transição suave e harmonia visual.

## Boas práticas de trabalho
- Antes de alterar, revisar as views e estilos relacionados para manter consistência.
- Fazer mudanças pontuais e fáceis de revisar; não refatorar além do que foi pedido.
- Se uma solicitação exigir tocar lógica/backend ou arquivos fora do escopo visual permitido (`views/` e estilos em `public/assets/css/`), **não executar**: explicar a restrição e sugerir o que pode ser feito no visual dentro do escopo.

## Documentação da API (OpenAPI)
- A especificação fica em `docs/openapi.yaml` e retrata as rotas de `routes/web.php` (exceção autorizada ao escopo visual).
- **Sempre que uma rota for adicionada, alterada ou removida em `routes/web.php`, atualizar `docs/openapi.yaml` na mesma mudança**: path, método, parâmetros (query/path/header), corpo do formulário, respostas e autenticação.
- Ao alterar um formulário em `views/` que faz POST para uma rota existente, conferir se os campos enviados continuam batendo com o schema correspondente em `components/schemas` e atualizar a documentação se necessário.
- Manter a especificação em pt-BR, com `openapi: 3.0.3` e YAML válido; usar componentes reutilizáveis para parâmetros e schemas repetidos.
- Não documentar rotas que não existam em `routes/web.php` (ex.: arquivos estáticos servidos pelo PHP embutido).
