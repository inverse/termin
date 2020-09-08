# Termin

A simple PHP script for notifying for a free slot for any appointment listed on the [Berlin appointments website][0].

[![Actions Status](https://github.com/inverse/termin/workflows/CI/badge.svg)](https://github.com/inverse/termin/actions)

![](https://i.imgur.com/8vxmVo2.png)

## Notifications

Currently only supports notifications via:

- Pushbullet
- Telegram

# Requirements

- PHP 7.3+
- composer

## Setup

- `composer install`
- Configure `.env` based on the `.env.dist` with JSON encoded site information and notifier settings

- Setup CRON job to call `app.php` on desired run interval e.g.

 `*/30 * * * *  php ~/termin/current/app.php`

- Wait for a notification

## Licence 

MIT

[0]: https://service.berlin.de/terminvereinbarung/
