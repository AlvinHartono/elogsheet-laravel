# syntax=docker/dockerfile:1.4
FROM php:8.3-apache

# Install dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libwebp-dev \
    tzdata \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mysqli zip pdo pdo_mysql
RUN docker-php-ext-configure gd --with-jpeg --with-freetype --with-webp
RUN docker-php-ext-install gd

# Set timezone ke Asia/Jakarta
ENV TZ=Asia/Jakarta
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone

# Enable Apache mod_rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy source code (hati2 kalau di Windows gunakan .dockerignore biar vendor/node_modules tidak ikut)
COPY . .
# RUN touch .env && echo "APP_KEY=" > .env
# Copy composer dari image resmi
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer
# Install dependencies Laravel
