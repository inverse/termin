FROM composer:latest AS composer

COPY composer.json /app

RUN composer install

FROM php:8.1-alpine

RUN apk add --no-cache unzip

COPY --from=composer /app/vendor /app/vendor

COPY . /app

WORKDIR /app

CMD [ "php", "app.php" ]
