FROM php:8.2-apache

RUN apt update && apt install -y \
    git vim unzip curl libzip-dev && \
    docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN composer self-update
    
    
RUN docker-php-ext-install sockets

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80