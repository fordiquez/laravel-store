<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# E-commerce project with Laravel 10, Vue 3 and Inertia.js

## Must have requirements

-   **Docker & Docker Compose.**

## Docker installation workflow

### 1. Clone this repository to your local folder

```bash
git clone git@github.com:fordiquez/laravel-store.git
```

```bash
cd laravel-store
```

### 2. Create .env

```bash
cp .env.example .env
```

### 3. Setup .env variables

#### 3.1 Set up base url for your application

```dotenv
APP_URL=
```

#### 3.2 Set up your database credentials

```dotenv
DB_CONNECTION=sqlite
#DB_HOST=mysql
#DB_PORT=3306
#DB_DATABASE=brandford
#DB_USERNAME=root
#DB_PASSWORD=root
```

#### 3.3 Set up your cache & session driver, filesystem disk & queue connection

```dotenv
CACHE_DRIVER=database
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database
SESSION_DRIVER=database
```

#### 3.3 Set up mail SMTP options

```dotenv
MAIL_MAILER=
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=
```

#### 3.4 Set up `multiavatar` API key

```dotenv
MULTIAVATAR_API_KEY=
```

#### 3.5 Set up `Countries States Cities` API key

```dotenv
CSC_API_KEY=
```

#### 3.7 Setup `Stripe` keys

```dotenv
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
docker exec -it store-laravel php artisan horizon
```

```bash
docker exec -it store-laravel /bin/bash
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
php artisan db:seed --class=RoleSeeder
```

```bash
php artisan optimize:clear
```

### 7. Run dev server

```bash
npm run dev
```

### 8. Run stripe webhook

```bash
stripe login
```

```bash
stripe listen --forward-to laravel-store.test/stripe/webhook
```

### 9. Edit hosts file

**Path for Windows:**
```
C:\Windows\System32\drivers\etc
```

**Add your application domain:**

```ini
127.0.0.1       laravel-store.test
```
