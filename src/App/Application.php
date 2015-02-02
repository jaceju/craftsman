<?php

namespace App;

use CLIFramework\Application as CliApp;

class Application extends CliApp
{
    const NAME = 'Craftsman';
    const VERSION = '0.0.2';

    public function options($opts)
    {
        parent::options($opts);
    }

    public function init()
    {
        parent::init();
        $this->command('init');
        $this->command('self-build');
    }
}
