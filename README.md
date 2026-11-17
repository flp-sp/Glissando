# Glissando
O sistema é uma plataforma de streaming de músicas onde existem usuários e músicas, os usuários cadastrados podem ouvir e fazer upload de novas músicas. Os usuários podem seguir outros usuários, curtir e salvar músicas em suas próprias playlists.

## Stack
O sistema vai ser uma aplicação web, e vai utilizar:
- php
- Neon
- postgreSQL
- JavaScript
- Chart.js
- HTML
- CSS

## Como rodar
Primeiro garanta que você tem o `php` instalado. Depois configure o seu .env seguindo o exemplo `.env.example` com as configurações do seu banco.

Depois rode o composer para gerar as pastas do autoload:

```bash
composer dump-autoload
```

Para rodar o sistema basta executar:
```bash
php serve.php
```

> [!TIP]
> Você pode alterar a porta e o host no arquivo `serve.php`

## Arquitetura
### Route
Define a url que realiza a ação

### Controller
Recebe a requisição e coordena a execução

### Service
Garante as regras de negócio

### Repository
Executa as operações no banco de dados

### Model
Gerencia as entidades e seus dados

### View
Retorna a resposta para o usuário

### Config
Fornece as estruturas para configuração como conexão PDO e `.env`

## Banco de dados
### Usuários
| Chave            | Descrição                                      |
| ---------------- | ---------------------------------------------- |
| id               |                                                |
| name             | Nome real do usuário                           |
| user_name        | Nome que será apresentado no perfil do usuário |
| pass_hash        | Senha armazenada em hash                       |
| email            | Email do usuário                               |
| datetime_created | Data de criação da conta                       |

### Músicas
| Chave           | Descrição                                                    |
| --------------- | ------------------------------------------------------------ |
| id              |                                                              |
| name            | Nome da música                                               |
| artist          | Nome do artista ou banda                                     |
| cover_path      | Caminho para a capa se tiver                                 |
| datetime_upload | Data do envio da música                                      |
| views           | Quantidade de vezes que alguém acessou a página dessa música |
| fk_user_upload  | Chave estrangeira do usuário que fez o envio                 |

### Inscrições
| Chave           | Descrição                              |
| --------------- | -------------------------------------- |
| id              |                                        |
| fk_id_followed  | Chave estrangeira do usuário seguido   |
| fk_id_following | Chave estrangeira do usuário que segue |

### Playlist
| Chave            | Descrição                                           |
| ---------------- | --------------------------------------------------- |
| id               |                                                     |
| name             | Nome da playlist                                    |
| datetime_created | Data da criação da playlist                         |
| fk_id_owner      | Chave estrangeira da usuário que é dono da playlist |

### PlaylistMusic
| Chave          | Descrição                     |
| -------------- | ----------------------------- |
| id             |                               |
| fk_id_playlist | Chave estrangeira da playlist |
| fk_id_music    | Chave estrangeira da música   |

### Curtidas
| Chave    | Descrição                   |
| -------- | --------------------------- |
| id       |                             |
| fk_music | Música curtida              |
| fk_user  | Usuário que curtiu a música |