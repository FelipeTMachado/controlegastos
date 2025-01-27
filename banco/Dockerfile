FROM php:8.2-cli 

RUN apt update && apt install -y \
    git vim unzip curl libzip-dev && \
    docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN composer self-update

# Instalar e ativar a extensão pcntl
RUN docker-php-ext-install pcntl && \
    docker-php-ext-enable pcntl && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install pdo pdo_mysql
    
# RUN docker-php-ext-install sockets

WORKDIR /var/www/html

COPY . .

RUN chown -R www-data:www-data /var/www/html