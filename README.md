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

- PHP 7.4+
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

Configure an `config.yml` file based on the `config.yml.example` file located in the root of the repostiory.

#### Notifications

Termin supports various notifiers, it will send notifications to each one you configure.

##### Push bullet (Easiest)

Set `pushbullet.api_token` within `config.yml` with an API token from your account. Follow their [quick start guide][3] on how to get this.

Make sure you install their application or browser extension.

##### Telegram

Follow the [official documentation][4] on setting up a bot.

Within `config.yml` Set `telegram.api_key` with the API key provided from this process.

Next add your bot to a group chat with yourself. 

Find the chat ID for your that group and set the value in `telegram.chat_id`.

##### Site configuration

```yaml
sites:
  -
    label: Vaterschaftsanerkennung
    url: https://service.berlin.de/terminvereinbarung/termin/tag.php?termin=1&dienstleister=122900&anliegen[]=318991&herkunft=1
  -
    label: Geburtsurkunde
    url: https://service.berlin.de/terminvereinbarung/termin/tag.php?termin=1&dienstleister=122900&anliegen[]=318957&herkunft=1
```

- `label` - the friendly label that will be displayed in the notification
- `url` - the service URL you want to book. e.g. the URL behind the "Book an Appointment button".

![](https://i.imgur.com/zqSScD5.png)

### Run

Setup a cron job to call `app.php` on desired run interval e.g. every hour

 ```bash
0 * * * *  php ~/path/to/termin/app.php
```

_Note: Don't set the frequency to high to not overload their website_

Wait for a notification!

### Run (docker)

Configure the application following the above steps and then run the prebuilt docker image.

```bash
docker run -it -v $(pwd)/config.yml:/app/config.yml inversechi/termin:latest
```

Configure this to run on a regular schedule using something that your OS provides.

_Note: Don't set the schedule frequency to high to not overload their website_


## Licence

MIT

[0]: https://service.berlin.de/terminvereinbarung/
[1]: https://www.pushbullet.com/
[2]: https://telegram.org/
[3]: https://docs.pushbullet.com/#api-quick-start
[4]: https://core.telegram.org/bots#3-how-do-i-create-a-bot
