<?php

use Inverse\Termin\Scraper;

require __DIR__.'/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$pushbulletApiToken = getenv('PUSHBULLET_API_TOKEN');
$scraper = new Scraper($pushbulletApiToken);


$sites = [
    'Vaterschaftsanerkennung' => 'https://service.berlin.de/terminvereinbarung/termin/tag.php?termin=1&dienstleister=122900&anliegen[]=318991&herkunft=1',
    'Geburtsurkunde' => 'https://service.berlin.de/terminvereinbarung/termin/tag.php?termin=1&dienstleister=122900&anliegen[]=318957&herkunft=1',
];

$scraper->scrape($sites);