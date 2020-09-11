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

Clone the repo

```basg
git clone https://github.com/inverse/termin.git
```

### Dependencies

Install dependencies
 
 ```bash
 composer install
```

### Configuration

Configure `.env` file based on the `.env.example` with JSON encoded site information and notifier settings.

If you provide more than one notifier configuration it will notify to both.

### Run

Setup a cron job to call `app.php` on desired run interval e.g. every hour

 ```bash
0 * * * *  php ~/path/to/termin/app.php
```

_Note: Don't set the frequency to high to not overload their website_

Wait for a notification!

## Licence

MIT

[0]: https://service.berlin.de/terminvereinbarung/
[1]: https://www.pushbullet.com/
[2]: https://telegram.org/
