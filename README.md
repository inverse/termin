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

Configure an `.env` file based on the `.env.example` with JSON encoded site information and notifier settings.

#### Push bullet (Easiest)

Set `PUSHBULLET_API_TOKEN` with an API token from your account. Follow their [quick start guide][3] on how to get this.

Make sure you install their application or browser extension 

If you provide more than one notifier configuration it will notify to both.

#### Telegram

TBD

#### Site configuration

`SITES` contains a JSON escaped list of site mapping information

e.g. `[{"label": "friendly label", "url": "the url to scrape"}]`


- `label` - the friendly label that will be displayed in the notification
- `url` - the service URL you want to book. e.g. the URL behind the "Book an Appointment button".

![](https://i.imgur.com/zqSScD5.png)

_note: make sure to escape it so it can be encoded within the `.env` file._

### Run

Setup a cron job to call `app.php` on desired run interval e.g. every hour

 ```bash
0 * * * *  php ~/path/to/termin/app.php
```

_Note: Don't set the frequency to high to not overload their website_

Wait for a notification!

### Run (docker)

Configure the application following the above steps and then build the docker image.

```bash
docker build -t termin:local .
```

Once built you can execute the script.

```bash
docker run -it termin:local
```

Configure this to run on a regular schedule using something that your OS provides.

_Note: Don't set the schedule frequency to high to not overload their website_


## Licence

MIT

[0]: https://service.berlin.de/terminvereinbarung/
[1]: https://www.pushbullet.com/
[2]: https://telegram.org/
[3]: https://docs.pushbullet.com/#api-quick-start
