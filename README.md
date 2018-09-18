# Termin

Simple PHP script for notifying for a free slot in Berlin.

Currently only supports notifications via Pushbullet.

## Setup

- `composer install`
- Configure `.env` based on the `.env.dist` with sites and a pushbullet API key

- Setup CRON job to call `app.php` on desired run interval e.g.

 `*/5 * * * *  php ~/termin/current/app.php`

- Wait for a notification

## Licence 

MIT