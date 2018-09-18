<?php


use Inverse\Termin\Container;

require __DIR__.'/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$container = new Container();




$siteParser = $container->getSiteParser();

$siteParser->parse(getenv('SITES'));

$scraper = $container->getScraper();

foreach ($sites as $name => $url) {
    $scraper->scrapeSite($name, $url);
}

