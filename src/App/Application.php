<?php

namespace App;

use CLIFramework\Application as CliApp;

class Application extends CliApp
{
    const NAME = 'Craftsman';
    const VERSION = '@package_version@';
    const REPOSITORY = 'jaceju/clitool-boilerplate';

    public function options($opts)
    {
        parent::options($opts);
    }

    public function init()
    {
        parent::init();
        $this->command('init');
        $this->command('self-update');
    }
}
