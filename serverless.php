<?php

declare(strict_types=1);

use Bref\Context\Context;
use Inverse\Termin\Config\ConfigLoader;
use Inverse\Termin\Config\ConfigParser;
use Inverse\Termin\Container;
use Inverse\Termin\Runtime;

require __DIR__.'/vendor/autoload.php';

class Handler implements Bref\Event\Handler
{
    public function handle($event, Context $context)
    {
        $configLoader = new ConfigLoader(__DIR__);
        $configParser = new ConfigParser();
        $config = $configParser->parse($configLoader->load());

        $container = new Container($config, Runtime::SERVERLESS);

        $termin = $container->getTermin();
        $termin->run($config->getSites());

        return 'OK';
    }
}

return new Handler();
