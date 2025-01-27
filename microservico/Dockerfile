FROM php:8.2-cli 

RUN apt update && apt install -y \
    git vim unzip curl libzip-dev && \
    docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN composer self-update


RUN docker-php-ext-install pcntl && \
    docker-php-ext-enable pcntl && \
    docker-php-ext-install bcmath
    
# RUN docker-php-ext-install sockets

WORKDIR /var/www/html
# COPY ./logs/processo_usuario.log /var/log/

COPY . .

RUN chown -R www-data:www-data /var/www/html