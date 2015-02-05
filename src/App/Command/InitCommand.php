<?php

namespace App\Command;

use CLIFramework\Command;
use CLIFramework\Prompter;
use App\JsonFile;

class InitCommand extends Command
{

    public function ask($prompt, $validAnswers = null, $default = null)
    {
        $prompter = new Prompter;
        $prompter->setStyle('ask');
        return $prompter->ask($prompt, $validAnswers, $default);
    }

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
    }

    protected function checkTool($name, $info)
    {
        $cmd = $info['cmd'];
        $pattern = $info['pattern'];
        $minVersion = $info['minVersion'];

        $tool = exec($cmd);

        if ('' === $tool) {
            $status = $this->formatter->format('✗', 'red');
            $result = false;
        } else {
            $status = $this->formatter->format('✓', 'green');

            $matches = [];
            preg_match("#$pattern#", $tool, $matches);
            $version = isset($matches[1]) ? $matches[1] : '';
            $result = (bool) version_compare($version, $minVersion, '>=');
        }

        $this->logger->info("$status $name $version");

        return $result;
    }

    protected function checkEnv()
    {
        $tools = [
            'Ruby' => [
                'cmd' => 'which ruby && ruby --version',
                'pattern' => '^ruby ([\d\.]+).*$',
                'minVersion' => '1.9.3',
            ],
            'Gem Bundle' => [
                'cmd' => 'which bundle && bundle --version',
                'pattern' => '([\d\.]+)$',
                'minVersion' => '1.7.0',
            ],
            'Node' => [
                'cmd' => 'which node && node --version',
                'pattern' => '([\d\.]+)$',
                'minVersion' => '0.10.2',
            ],
            'npm' => [
                'cmd' => 'which npm && npm --version',
                'pattern' => '([\d\.]+)$',
                'minVersion' => '2.2.0',
            ],
        ];

        $status = true;
        foreach ($tools as $name => $info) {
            $status = $status && $this->checkTool($name, $info);
        }

        return $status;
    }

    protected function getLaravelVersion($path)
    {
        $composer = new JsonFile($path . '/composer.json');

        $version = isset($composer->info->{"require"}->{"laravel/framework"})
                ? $composer->info->{"require"}->{"laravel/framework"}
                : null;

        if (!empty($version)) {
            $version = substr($version, 0, 1);
        }

        return $version;
    }

    protected function guessLaravelVersion($path)
    {
        $version = $this->getLaravelVersion($path);

        if (null === $version) {
            return null;
        }

        $checkList = [
            '4' => [
                '/app/routes.php',
                '/app/config/app.php',
            ],
            '5' => [
                '/app/Http/routes.php',
                '/config/app.php',
            ],
        ];

        $exists = true;
        foreach ($checkList[$version] as $file) {
            $exists = $exists && file_exists($path . $file);
        }

        return $exists ? $version : null;
    }

    protected function chooseProjectType()
    {
        return $this->choose('What is your project type?', [
            'Laravel 4' => 'laravel4',
            'Laravel 5' => 'laravel5',
            'Custom' => 'custom',
        ]);
    }

    public function execute($path = null)
    {
        $this->logger->info('Checking environment...');

        if (!$this->checkEnv()) {
            $this->logger->info('Sorry, I can not initial the project. :(');
            return false;
        }

        if (null === $path) {
            $path = getcwd();
        }

        $version = $this->guessLaravelVersion($path);
        if (null !== $version) {
            $msg = 'I guess this project is based on Laravel ';
            $msg .= $version . ', am I right?';
            $ans = $this->ask($msg, ['y', 'n'], 'y');

            if ('n' === $ans) {
                $type = $this->chooseProjectType();
            } else {
                $type = 'laravel' . $version;
            }
        } else {
            $type = $this->chooseProjectType();
        }

        $templateDir = __DIR__ . '/../../templates';

        $this->copy($templateDir . '/root', $path);
        $this->copy($templateDir . '/app', $path . '/app');
        $this->copy($templateDir . '/tasks', $path . '/tasks');
        copy($templateDir . '/config/config.' . $version . '.js', $path . '/tasks/config.js');

        $this->rename($path . '/bowerrc', '.bowerrc');
        $this->rename($path . '/jshintrc', '.jshintrc');
        $this->rename($path . '/tasks/config.' . $version . '.js', 'config.js');

        switch ($type) {
            case 'laravel5':
                $this->rename($path . '/resources/views', 'templates');
                $this->copy($templateDir . '/assets', $path . '/resources/assets');
                break;
            case 'laravel4':
                $this->rename($path . '/app/views', 'templates');
                $this->copy($templateDir . '/assets', $path . '/assets');
                break;
            default:
                break;
        }

        $this->logger->writeln($this->getFormatter()->format('Initialize...', 'green'));
        chdir($path);
        exec('sh < build.sh');

        $this->logger->writeln('Done! :D');
        $this->logger->newline();
        $this->logger->writeln('Insert these lines to your template files, I can\'t do this for you: :/');
        $this->logger->newline();

        $initMessage = file_get_contents(__DIR__ . '/../../messages/init.txt');
        $this->logger->info($this->getFormatter()->format($initMessage, 'yellow'));

        $this->logger->writeln('You can run `gulp` to build or `gulp watch` for development.');

        return $version;
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
