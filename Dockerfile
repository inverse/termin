FROM composer:latest AS composer

FROM php:7.4-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache unzip

WORKDIR /app

COPY . /app

RUN composer install

CMD [ "php", "app.php"]
