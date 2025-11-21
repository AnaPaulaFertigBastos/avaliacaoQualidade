# avaliacaoQualidade
Esse sistema permitirá que clientes ou outras pessoas possam avaliar os diversos setores do estabelecimento por meio de dispositivos como tablets espalhados em diferentes ambientes.


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


## Como rodar o projeto (Windows + PostgreSQL)

### Requisitos
- PHP 8.1+ e Composer 2
- PostgreSQL 13+ (ou compatível)
- Extensão PHP pdo_pgsql habilitada
- Opcional: Node.js 18+ (apenas se for compilar assets com Vite)

### Passo a passo
1) Instale dependências PHP

```powershell
composer install
```

2) Crie o arquivo `.env` a partir do exemplo e configure o banco

```powershell
Copy-Item .env.example .env
```

Edite `.env` (existe o `.env.example` para copiar e colar) e ajuste (exemplo usando PostgreSQL local com DB `avaliacoessistema`):

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=avaliacoessistema
DB_USERNAME=postgres
DB_PASSWORD=senha
```

3) Crie o banco de dados `avaliacoessistema` no PostgreSQL (via pgAdmin ou SQL):

```sql
CREATE DATABASE avaliacoessistema;
```

4) Gere a chave da aplicação

```powershell
php artisan key:generate
```

5) Execute as migrations (as migrations de inserção já populam perguntas, setores e dispositivos)

```powershell
php artisan migrate
```

Opcional (para começar do zero a qualquer momento):

```powershell
php artisan migrate:fresh
```

6) Suba o servidor de desenvolvimento

```powershell
php artisan serve
```

Abra em: `http://127.0.0.1:8000`

### Usando a avaliação pública
- A rota pública aceita parâmetros de rota: `/avaliacao/{setorId?}/{dispositivoId?}`
- Para testar rapidamente, recupere um `setorId` e `dispositivoId` existentes, como esses, já cadastrados:

http://127.0.0.1:8000/avaliacao/8a1b2c3d-1e2f-4a3b-9c4d-7e8f90123456/d1e2f3a4-6b7c-4f9a-8b1c-234567890cde




Ou outro setorId e dispositivoId existentes:

```
http://127.0.0.1:8000/avaliacao/<setorId>/<dispositivoId>


```

As perguntas são carregadas por AJAX e o formulário envia as respostas para o backend.

### Compilação de assets (opcional)
O projeto já inclui CSS/JS prontos em `public/`. Se desejar trabalhar com os arquivos em `resources/` via Vite:

```powershell
npm install
npm run build
```

### Credenciais de admin

- **E-mail:** `ana.bastos@unidavi.edu.br`
- **Senha:** `admin123`

### Dicas e problemas comuns
- Alterou rotas/nomes e algo ficou em cache? Limpe caches com:

```powershell
php artisan optimize:clear
```

- Conexão com banco falhou: confirme host/porta/usuário/senha no `.env` e se o DB `avaliacoessistema` existe.
- Erros de migração: use `php artisan migrate:fresh` em ambiente local para recriar as tabelas.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Admin — Como usar a rota `admin`

Esta seção descreve as rotas principais do painel de administração (`/admin`) e como usá-las em desenvolvimento.

- **GET `/admin/login`** — mostra o formulário de login do admin. (Nome da rota: `admin.login`).
- **POST `/admin/login`** — envia credenciais e autentica (Nome da rota: `admin.login.post`).
- **POST `/admin/logout`** — encerra a sessão do admin (Nome da rota: `admin.logout`).
- **GET `/admin/` ou `/admin/dashboard`** — painel do admin (Nome da rota: `admin.dashboard`) — protegido por `auth`.

Como usar na prática
- Acesse no navegador `http://127.0.0.1:8000/admin/login` — você será levado ao login ou ao dashboard conforme estiver autenticado.
```
