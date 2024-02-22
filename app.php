<?php

declare(strict_types=1);

use Inverse\Termin\Config\ConfigParser;
use Inverse\Termin\Container;
use Symfony\Component\Yaml\Yaml;

require __DIR__.'/vendor/autoload.php';

$configLoader = new ConfigParser();
$config = $configLoader->parse(Yaml::parseFile(__DIR__.'/config.yml'));

$container = new Container($config);

$termin = $container->getTermin();
$termin->run($config->getSites());
