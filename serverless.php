<?php
 
use Bref\Context\Context;
use Inverse\Termin\Config\ConfigParser;
use Inverse\Termin\Container;
use Symfony\Component\Yaml\Yaml;

require __DIR__.'/vendor/autoload.php';


class Handler implements \Bref\Event\Handler
{
    public function handle($event, Context $context)
    {
        $configLoader = new ConfigParser();
        $config = $configLoader->parse(Yaml::parseFile(__DIR__ . '/config.yml'));
        
        $container = new Container($config, 'serverless');
        
        $termin = $container->getTermin();
        $termin->run($config->getSites());
        
        return 'OK';
    }
}
 
return new Handler();
