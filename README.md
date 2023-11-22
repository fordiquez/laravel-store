<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# E-commerce project with Laravel 10, Vue 3 and Inertia.js

## Must have requirements

-   **PHP v8.1;**
-   **Composer v2.0;**
-   **MySQL;**
-   **NPM;**
-   **Docker;**

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

#### 3.7 Setup Stripe keys

```
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=
```

### 4. Install all composer & npm dependencies

```bash
composer install
```

```bash
npm install
```

### 5. Docker settings

```bash
docker-compose up --build -d
```

```bash
docker exec -it brandford_app /bin/bash
```

### 6. Run artisan commands

```bash
php artisan key:generate
```

```bash
php artisan storage:link
```

```bash
php artisan migrate:fresh --seed
```

```bash
php artisan shield:install --fresh
```

```bash
php artisan optimize:clear
```

### 7. Run application server & SSR rendering

```bash
npm run dev
```

```bash
php artisan inertia:start-ssr
```

```bash
php artisan serve --host=brandford.local.io
```

### 8. Run stripe webhook

```bash
stripe login
```

```bash
stripe listen --forward-to brandford.local.io/stripe/webhook
```

```bash
stripe trigger payment_intent.succeeded
```

### 9. Edit hosts file (Windows 10)

**Add your application domain**

```
C:\Windows\System32\drivers\etc
```

**Add the next line:**

```
127.0.0.1 brandford.local.io
```
