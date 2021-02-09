FROM composer:latest AS composer

FROM php:7.4-buster

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y unzip && apt-get clean

WORKDIR /app

COPY . /app

RUN composer install

CMD [ "php", "app.php"]
