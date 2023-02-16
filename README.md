<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# E-commerce project with Laravel 9, Vue 3 and Inertia.js

## Must have requirements

-   PHP v8.0+
-   Composer
-   MySQL
-   Node
-   NPM

## Local installation workflow

### 1. Clone this repository to your local folder

```
git clone https://github.com/fordiquez/laravel-store.git
```

```
cd laravel-store
```

### 2. Create .env

```
cp .env.example .env
```

### 3. Setup .env variables

#### 3.1 Setup base url for your application

```
APP_URL=
```

#### 3.2 Setup your database configuration

```
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

#### 3.3 Setup mail SMTP options

```
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=
```

#### 3.4 If you are using Redis for queue jobs, then change connection & setup requirement credentials

```
QUEUE_CONNECTION=redis
```

```
REDIS_HOST=
REDIS_PASSWORD=
REDIS_PORT=
```

#### 3.5 Setup multiavatar API key

```
MULTIAVATAR_API_KEY=
```

#### 3.6 Setup Countries States Cities API key

```
CSC_API_KEY=
```

### 4. Install all composer & npm dependencies

```
composer install
```

```
npm install
```

### 5. Run artisan commands

```
php artisan key:generate
```

```
php artisan storage:link
```

```
php artisan migrate --seed
```

```
php artisan optimize:clear
```

### 6. Run application server & SSR rendering

```
npm run dev
```

```
php artisan inertia:start-ssr
```
