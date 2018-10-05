# Termin

A simple PHP script for notifying for a free slot for any appointent lised on the [Berlin appointments website][0].

## Notifications

Currently only supports notifications via Pushbullet.

# Requirements

- PHP 7.1+
- composer

## Setup

- `composer install`
- Configure `.env` based on the `.env.dist` with JSON encoded site information and a pushbullet API key

- Setup CRON job to call `app.php` on desired run interval e.g.

 `*/5 * * * *  php ~/termin/current/app.php`

- Wait for a notification

## Licence 

MIT

[0]: https://service.berlin.de/terminvereinbarung/
