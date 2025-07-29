<?php

declare(strict_types=1);

use Inverse\Termin\Config\ConfigLoader;
use Inverse\Termin\Config\ConfigParser;
use Inverse\Termin\Container;

require __DIR__.'/vendor/autoload.php';

$configLoader = new ConfigLoader(__DIR__);

$configParser = new ConfigParser();
$config = $configParser->parse($configLoader->load());

$container = new Container($config);

$termin = $container->getTermin();
$termin->run($config->getSites());
