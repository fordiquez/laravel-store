FROM php:8.2-fpm

ARG user
ARG uid

RUN apt-get update \
    && apt-get install -y \
        libmcrypt-dev \
        wget \
        curl \
        git \
        libzip-dev \
        zip \
        libpq-dev \
        libxml2-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libwebp-dev \
        npm \
        sudo \
        libicu-dev;

RUN apt install supervisor
RUN pecl install redis

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql sockets zip soap intl bcmath gd
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-enable redis

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Set working directory
WORKDIR /var/www

USER $user
