<?php

namespace App\Command;

use CLIFramework\Command;

class InitCommand extends Command
{
    public function brief()
    {
        return 'Initialize your project for development.';
    }

    public function init()
    {
        parent::init();
        // register your subcommand here ..
    }

    // public function options($opts)
    // {
    //     $opts->add('assets-dir:', 'assets path of project.')
    //         ->isa('string')
    //         ->valueName('path');
    //     $opts->add('public-dir:', 'document root path of project.')
    //         ->isa('string')
    //         ->valueName('path');
    // }

    public function arguments($args)
    {
        $args->add('type')
            ->isa('string')
            ->validValues([null, 'laravel4', 'laravel5', 'custom']);
    }

    public function execute($type = null)
    {
        $this->logger->notice('executing bar command.');
        $this->logger->info('info message');
        $this->logger->debug('info message');
        $this->logger->write('just write');
        $this->logger->writeln('just drop a line');
        $this->logger->newline();

        if (null === $type) {
            $input = $this->ask('Chose your project type:');
        }

        return $type;
    }
}
