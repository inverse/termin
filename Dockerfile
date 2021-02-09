FROM composer:latest AS composer

FROM php:7.4-buster

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . /app

CMD [ "php", "app.php"]
