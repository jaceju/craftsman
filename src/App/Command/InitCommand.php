<?php

namespace App\Command;

use CLIFramework\Command;

class InitCommand extends Command
{
    public function brief()
    {
        return 'Initialize your project for development.';
    }

    public function options($opts)
    {
        parent::options($opts);

        $opts->add('path?', 'Specify path of project.')
            ->isa('string');
    }

    public function arguments($args)
    {
        $args->add('path')
            ->isa('string');

        $args->add('type')
            ->isa('string')
            ->validValues([null, 'laravel4', 'laravel5', 'custom']);
    }

    public function execute($path = null, $type = null)
    {
        $logger = $this->getLogger();
        /* @var \CLIFramework\Logger $logger */

        if (null === $path) {
            $path = getcwd();
        }

        if (null === $type) {
            $type = $this->choose('Chose your project type:', [
                'laravel4' => 'laravel4',
                'laravel5' => 'laravel5',
                'custom' => 'custom',
            ]);
        }

        $templateDir = __DIR__ . '/../../templates';

        $this->copy($templateDir . '/root', $path);
        $this->copy($templateDir . '/app', $path . '/app');
        $this->copy($templateDir . '/tasks', $path . '/tasks');
        copy($templateDir . '/config/config.' . $type . '.js', $path . '/tasks/config.js');

        $this->rename($path . '/bowerrc', '.bowerrc');
        $this->rename($path . '/jshintrc', '.jshintrc');
        $this->rename($path . '/tasks/config.' . $type . '.js', 'config.js');

        switch ($type) {
            case 'laravel5':
                $this->copy($templateDir . '/assets', $path . '/resources/assets');
                break;
            case 'laravel4':
            default:
                $this->copy($templateDir . '/assets', $path . '/assets');
                break;
        }

        $logger->writeln($this->getFormatter()->format('Initialize...', 'green'));
        chdir($path);
        exec('sh < build.sh');

        $logger->writeln('Done!');
        $logger->newline();
        $logger->writeln('Insert these lines to your template files, I can\'t do this for you:');
        $logger->newline();

        $initMessage = file_get_contents(__DIR__ . '/../../messages/init.txt');
        $logger->info($this->getFormatter()->format($initMessage, 'yellow'));

        $logger->writeln('You can run `gulp` to build or `gulp watch` for development.');

        return $type;
    }

    public function copy($source, $dest)
    {
        @mkdir($dest, 0755);
        foreach (
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            ) as $item
        ) {
            if ($item->isDir()) {
                @mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                @copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }
    }

    public function rename($source, $newName)
    {
        $fullName = dirname($source) . '/' . $newName;
        @rename($source, $fullName);
    }
}
