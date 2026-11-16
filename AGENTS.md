# AGENTS.md — Glissando

## Papel e escopo
- Atuar **apenas no front-end/visual** do Glissando.
- **Restrição principal:** alterações limitadas a `views/` (templates/markup e apresentação contida nas views).
- **Não alterar a lógica por trás do código:** nada de regras de negócio, controllers, services, repositories, models, rotas, configuração, banco de dados, autenticação, validações de backend ou fluxo de dados.
- Mexer **somente no visual e em elementos previamente delimitados pelo usuário** para cada tarefa. Se houver dúvida sobre o que está delimitado, perguntar antes de editar.

## Estilo visual (manter o tom atual)
- Preservar o estilo **simples, limpo e coerente** já presente no projeto.
- Respeitar a identidade visual existente (ex.: paleta `--c1` a `--c5`, gradientes suaves, cantos arredondados, tipografia legível, animações leves) e evitar exageros visuais.
- Priorizar consistência entre páginas, boa leitura, espaçamento equilibrado e responsividade básica.
- Não introduzir frameworks/bibliotecas visuais ou mudanças estruturais amplas sem pedido explícito.

## Tom e referências musicais
- Sempre que possível, trazer **referências a músicas, ritmos, instrumentos, termos musicais ou metáforas sonoras** de forma sutil e contextual (nomes, textos de apoio, microcopy, classes/comentários visuais quando fizer sentido), sem comprometer clareza ou usabilidade.
- Manter o espírito do nome **Glissando**: fluidez, transição suave e harmonia visual.

## Boas práticas de trabalho
- Antes de alterar, revisar as views e estilos relacionados para manter consistência.
- Fazer mudanças pontuais e fáceis de revisar; não refatorar além do que foi pedido.
- Se uma solicitação exigir tocar lógica/backend ou arquivos fora de `views/`, **não executar**: explicar a restrição e sugerir o que pode ser feito no visual dentro do escopo.
