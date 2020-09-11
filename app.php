<?php

use Inverse\Termin\Container;

require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$container = new Container();

$siteParser = $container->getSiteParser();

$sites = $siteParser->parse(getenv('SITES'));

$termin = $container->getTermin();
$termin->run($sites);
