<?php

namespace Inverse\Termin\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExecuteCommand extends Command
{
    protected static $defaultName = 'app:execute';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('yo');

        return 0;
    }
}