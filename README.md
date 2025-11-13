# avaliacaoQualidade
Esse sistema permitirá que clientes ou outras pessoas possam avaliar os diversos setores do estabelecimento por meio de dispositivos como tablets espalhados em diferentes ambientes.


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

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

Edite `.env` e ajuste (exemplo usando PostgreSQL local com DB `avaliacoessistema`):

```
APP_NAME="AvaliacaoQualidade"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=avaliacoessistema
DB_USERNAME=postgres
DB_PASSWORD=postgres
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
- Para testar rapidamente, recupere um `setorId` e `dispositivoId` existentes:

```powershell
php artisan tinker
```

No Tinker:

```php
>>> \App\Models\Setor::first()->id;
>>> \App\Models\Dispositivo::first()->id;
```

Depois acesse no navegador:

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

### Dicas e problemas comuns
- Alterou rotas/nomes e algo ficou em cache? Limpe caches com:

```powershell
php artisan optimize:clear
```

- Conexão com banco falhou: confirme host/porta/usuário/senha no `.env` e se o DB `avaliacoessistema` existe.
- Erros de migração: use `php artisan migrate:fresh` em ambiente local para recriar as tabelas.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
