# Termin

A simple PHP script for notifying for a free slot for any appointment listed on the [Berlin services website][0].

[![Actions Status](https://github.com/inverse/termin/workflows/CI/badge.svg)](https://github.com/inverse/termin/actions)
[![codecov](https://codecov.io/gh/inverse/termin/branch/master/graph/badge.svg)](https://codecov.io/gh/inverse/termin)


![](https://i.imgur.com/8vxmVo2.png)

## Notifications

Currently supports notifications via:

- [Pushbullet][1]
- [Telegram][2]

# Requirements

- PHP 7.3+
- composer

## Setup

Install PHP dependencies by running `composer install`.

Configure `.env` based on the `.env.dist` with JSON encoded site information and notifier settings.

Setup CRON job to call `app.php` on desired run interval e.g.

 ```bash
*/30 * * * *  php ~/path/to/termin/app.php
```

Wait for a notification!

## Licence 

MIT

[0]: https://service.berlin.de/terminvereinbarung/
[1]: https://www.pushbullet.com/
[2]: https://telegram.org/
