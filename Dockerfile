FROM composer:latest AS composer

COPY composer.json /app

RUN composer install --no-dev --optimize-autoloader

FROM php:8.1-alpine

RUN apk add --no-cache unzip

COPY . /app

COPY --from=composer /app/vendor /app/vendor

WORKDIR /app

CMD [ "php", "app.php" ]
