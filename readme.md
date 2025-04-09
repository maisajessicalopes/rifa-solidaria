<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of any modern web application framework, making it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 1100 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for helping fund on-going Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell):

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[British Software Development](https://www.britishsoftware.co)**
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)
- [User10](https://user10.com)
- [Soumettre.fr](https://soumettre.fr/)
- [CodeBrisk](https://codebrisk.com)
- [1Forge](https://1forge.com)
- [TECPRESSO](https://tecpresso.co.jp/)
- [Pulse Storm](http://www.pulsestorm.net/)
- [Runtime Converter](http://runtimeconverter.com/)
- [WebL'Agence](https://weblagence.com/)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


R# Clona o projeto (se necessário)
git clone https://github.com/seu-usuario/rifa-solidaria.git
cd rifa-solidaria

# Instala as dependências
composer install

# Copia o arquivo de exemplo de ambiente
cp .env.example .env

# Gera a chave da aplicação
php artisan key:generate

# Cria o banco de dados (ajuste no .env se necessário)

# Executa as migrations e popula os números da rifa
php artisan migrate:fresh --seed

Perfeito, Maisa! Aqui está um `README.md` completo para seu projeto **Rifa Solidária**, com instruções de instalação, uso, comandos e observações:

---

### 📄 `README.md`

```markdown
# 🎟️ Rifa Solidária

Sistema simples para gerenciamento de uma rifa com 450 números, onde usuários podem reservar números, visualizar os seus e o sorteio só é habilitado após todos os números estarem preenchidos.

---

## 🚀 Requisitos

- PHP >= 7.1.3
- Composer
- Laravel 5.5
- MySQL
- Node.js e NPM (opcional, caso use assets do Laravel Mix)

---

## ⚙️ Instalação

```bash
git clone https://github.com/seu-usuario/rifa-solidaria.git
cd rifa-solidaria
composer install
cp .env.example .env
php artisan key:generate
```

Configure seu `.env` com as informações do banco de dados:

```
DB_DATABASE=rifa
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

---

## 🧱 Migrations + Seed

Apague todas as tabelas existentes e crie novamente:

```bash
php artisan migrate:fresh --seed
```

> Isso criará as tabelas:
> - `users`
> - `password_resets`
> - `rifa_numeros` (com os 450 números gerados automaticamente via seeder)

---

## 👤 Autenticação

Laravel 5.5 vem com scaffold de autenticação pronto:

```bash
php artisan make:auth
```

---

## 📄 Views

As views principais são:

- `resources/views/rifa/index.blade.php` → Exibe todos os 450 números, cores, modal de reserva, botão de sorteio.
- `resources/views/rifa/meus_numeros.blade.php` → Lista os números reservados pelo usuário autenticado.

---

## 📦 Comandos úteis

- `php artisan migrate` → Executa as migrations
- `php artisan db:seed` → Executa os seeders
- `php artisan make:auth` → Gera as views e rotas de login/cadastro
- `php artisan serve` → Inicia servidor local

---

## ✅ Funcionalidades

- Login e cadastro de usuários
- Lista com todos os 450 números
- Reserva de número com nome e telefone
- Upload de comprovante (opcional)
- Painel "Meus números"
- Botão de sorteio habilitado apenas quando todos os números estiverem reservados

---

## 🛠️ Em desenvolvimento

- Painel administrativo
- Sistema de sorteio aleatório
- Envio de confirmação por e-mail

---

