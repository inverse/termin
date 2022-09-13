# Termin

A simple PHP script for notifying for a free slot for any appointment listed on the [Berlin services website][0].

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/764fcbdbe9dd4383ad808cb4f83159af)](https://app.codacy.com/gh/inverse/termin?utm_source=github.com&utm_medium=referral&utm_content=inverse/termin&utm_campaign=Badge_Grade_Settings)
[![Actions Status](https://github.com/inverse/termin/workflows/CI/badge.svg)](https://github.com/inverse/termin/actions)
[![codecov](https://codecov.io/gh/inverse/termin/branch/master/graph/badge.svg)](https://codecov.io/gh/inverse/termin)


![](https://i.imgur.com/8vxmVo2.png)

# Notifications

Currently supports notifications via:

- [Pushbullet][1]
- [Telegram][2]

# Requirements

- PHP 7.4+
- composer

# Setup

Clone the repo

```basg
git clone https://github.com/inverse/termin.git
```

## Dependencies

Install dependencies
 
 ```bash
 composer install
```

## Configuration

Configure an `config.yml` file based on the `example.config.yml` file located in the root of the repository.

### Notifications

Termin supports various notifiers, it will send notifications to each one you configure.

#### Push bullet (Easiest)

Set `pushbullet.api_token` within `config.yml` with an API token from your account. Follow their [quick start guide][3] on how to get this.

Make sure you install their application or browser extension.

#### Telegram

Follow the [official documentation][4] on setting up a bot.

Within `config.yml` Set `telegram.api_key` with the API key provided from this process.

You will first need to make communication with the bot to enable it to send messages to you. 

Once you have done that find your chat ID. You can get this by interacting with `@raw_data_bot` bot.

Set the value in `telegram.chat_id`.

##### Groups

If you are adding the bot to a group you must add your bot to your group.

Then find the chat ID for the group by adding `@raw_data_bot` and it will detail this.

Take care when copying the leading `-` as this is required for group chat IDs.

### Site configuration

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

### Rules

Recently introduced is a rule engine that can be used for only notifying when certain conditions are met. Uses
the PHP [DateInterval][5] construct for expressing date differences.

#### after

Only notify when found appointments happen after condition.

```yaml
rules:
  -
    type: after
    param: PT24H # Notify when the appointment is greater than 24h in the future
```

Uses the PHP [DateInterval][5] construct.

#### before

Only notify when found appointments happen before condition.

```yaml
rules:
  -
    type: before
    param: PT24H # Notify when the appointment is less than 24h in the future
```

Uses the PHP [DateInterval][5] construct.

#### after

Only notify when found appointments happen after date condition.

```yaml
rules:
  -
    type: after_date
    param: '2022-01-01 00:00:00'
```

#### before_date

Only notify when found appointments happen before date condition.

```yaml
rules:
  -
    type: before_date
    param: '2022-01-01 00:00:00' 
```

## Run

You can execute the script manually to test if everything is working correctly.

```bash
php app.php
```

If you see no errors and output similar to:

```
[2021-11-01T19:43:14.687782+00:00] termin.INFO: No availability found for: Anmeldung einer Wohnung [] []
```

You can then configure this to run on a regular schedule using something that your OS provides.

For example with cron, setup a job to call `app.php` on desired run interval e.g. every hour

 ```bash
0 * * * *  php ~/path/to/termin/app.php
```

_Note: Don't set the frequency to high to not overload their website_

Wait for a notification!

## Run (docker)

Configure the application following the above steps and then run the prebuilt docker image.

```bash
docker run -it -v $(pwd)/config.yml:/app/config.yml inversechi/termin:latest
```

Configure this to run on a regular schedule using something that your OS provides.

_Note: Don't set the schedule frequency to high to not overload their website_

# Licence

MIT

[0]: https://service.berlin.de/terminvereinbarung/
[1]: https://www.pushbullet.com/
[2]: https://telegram.org/
[3]: https://docs.pushbullet.com/#api-quick-start
[4]: https://core.telegram.org/bots#3-how-do-i-create-a-bot
[5]: https://www.php.net/manual/en/dateinterval.construct.php
