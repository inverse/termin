<?php

use Inverse\Termin\Scraper;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Pimple\Container;
use Psr\Log\LoggerInterface;
use Pushbullet\Pushbullet;

require __DIR__.'/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();


$container = new Container();

$container[Pushbullet::class] = function (Container $container) {
    return new Pushbullet(getenv('PUSHBULLET_API_TOKEN'));
};

$container[LoggerInterface::class] = function (Container $container) {
    $logger = new Logger('name');
    $logger->pushHandler(new StreamHandler(__DIR__.'/var/log/app.log', Logger::INFO));

    return $logger;
};

$container[Scraper::class] = function (Container $container) {
    return new Scraper($container[LoggerInterface::class], $container[Pushbullet::class]);
};

$sites = [
    'Vaterschaftsanerkennung' => 'https://service.berlin.de/terminvereinbarung/termin/tag.php?termin=1&dienstleister=122900&anliegen[]=318991&herkunft=1',
    'Geburtsurkunde' => 'https://service.berlin.de/terminvereinbarung/termin/tag.php?termin=1&dienstleister=122900&anliegen[]=318957&herkunft=1',
];


/** @var Scraper $scraper */
$scraper = $container[Scraper::class];

foreach ($sites as $name => $url) {
    $scraper->scrapeSite($name, $url);
}

