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

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).





# PHP 8.2 with PostgreSQL Dockerfile

This Dockerfile sets up a PHP 8.2 environment with PostgreSQL client and necessary extensions (`pgsql`, `pdo_pgsql`) to work with PostgreSQL databases.

## Dockerfile Overview

This Dockerfile performs the following tasks:

1. **Base Image:**
   - Uses the official `php:8.2-fpm` image as the base.

2. **Install Dependencies:**
   - Installs `libpq-dev`, which is required to compile PostgreSQL extensions.
   - Installs `postgresql-client`, which is necessary to interact with PostgreSQL databases via the `psql` command-line client.
   
3. **Install PHP Extensions:**
   - Configures and installs the `pgsql` and `pdo_pgsql` extensions for PostgreSQL support in PHP.
   
4. **Clean Up:**
   - Cleans up the package manager cache to reduce image size.

5. **Verify Extensions:**
   - Verifies that the `pdo_pgsql` extension has been installed correctly by running the `php -m` command and filtering for `pdo_pgsql`.

## Detailed Command Explanation

### 1. **FROM php:8.2-fpm**

This sets the base image for the container. We are using PHP 8.2 with FPM (FastCGI Process Manager), which is commonly used for PHP applications in web servers like Nginx.

### 2. **RUN apt-get update && apt-get install -y libpq-dev postgresql-client**

- `apt-get update`: Updates the package list for the system's package manager.
- `apt-get install -y libpq-dev postgresql-client`: Installs two packages:
  - `libpq-dev`: A development library that provides headers and files for compiling PostgreSQL extensions.
  - `postgresql-client`: Installs the `psql` client, which allows you to interact with PostgreSQL databases from the command line.

### 3. **RUN docker-php-ext-configure pgsql --with-pgsql=/usr/local/pgsql**

This command configures the `pgsql` PHP extension with the PostgreSQL installation path. It prepares the extension for installation by telling the PHP build system where PostgreSQL is located.

### 4. **RUN docker-php-ext-install pgsql pdo_pgsql**

Installs the `pgsql` and `pdo_pgsql` PHP extensions:
- `pgsql`: Allows PHP to communicate directly with PostgreSQL databases.
- `pdo_pgsql`: Provides PDO (PHP Data Objects) support for PostgreSQL, which is used for database interaction with a consistent interface.

### 5. **RUN rm -rf /var/lib/apt/lists/***

This command cleans up the local package cache, which helps reduce the final image size by removing unnecessary files created during package installation.

### 6. **RUN php -m | grep pdo_pgsql**

This command verifies that the `pdo_pgsql` extension is successfully installed by listing the installed PHP modules (`php -m`) and filtering for `pdo_pgsql`.

## How to Build and Run

### 1. Build the Docker Image

In the directory where your `Dockerfile` is located, open a terminal and run the following command to build the Docker image:

```bash
docker build -t php-postgres-image .
