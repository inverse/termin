<?php

use Inverse\Termin\Container;

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required('SITES');

$container = new Container();

$siteParser = $container->getSiteParser();
$sites = $siteParser->parse($_ENV['SITES']);

$termin = $container->getTermin();
$termin->run($sites);
