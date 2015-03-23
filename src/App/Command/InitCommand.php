<?php

namespace App\Command;

use CLIFramework\Command;
use CLIFramework\Prompter;
use App\Filesystem\Json as JsonFile;
use Exception;

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
        $version = '(not installed)';

        $tool = exec($cmd);
        $tool = preg_replace('#' . "\033" . '\[\d+m#', '', $tool);

        if ('' === $tool) {
            $result = false;
        } else {

            $matches = [];
            preg_match("#$pattern#", $tool, $matches);

            $version = isset($matches[1]) ? $matches[1] : '';
            $result = version_compare($version, $minVersion, '>=');

            if (!$result) {
                $version .= ' (require ' . $minVersion . '+)';
            }
        }

        $status = $result ? $this->formatter->format('✓', 'green')
                          : $this->formatter->format('✗', 'red');

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
                'minVersion' => '2.0.0',
            ],
            'Composer' => [
                'cmd' => 'which composer && composer --version',
                'pattern' => ' ([\d\.]+)',
                'minVersion' => '1.0',
            ]
        ];

        $this->logger->info('Checking environment...');
        $status = true;
        foreach ($tools as $name => $info) {
            $status = $status && $this->checkTool($name, $info);
        }

        if (!$status) {
            $this->logger->info('Sorry, I can not initial the project. :(');
        }

        return $status;
    }

    protected function getProjectPath($path)
    {
        if (null === $path) {
            $path = getcwd();
        }
        return $path;
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

    protected function isLaravel5($path)
    {
        $version = $this->getLaravelVersion($path);

        if (null === $version) {
            return false;
        }

        $checkList = [
            '/app/Http/routes.php',
            '/config/app.php',
        ];

        $exists = true;
        foreach ($checkList as $file) {
            $exists = $exists && file_exists($path . $file);
        }

        return $exists;
    }

    protected function generateFiles($path)
    {
        $templateDir = __DIR__ . '/../../templates';

        $this->copy($templateDir . '/root', $path);
        $this->copy($templateDir . '/app', $path . '/app');

        $this->rename($path . '/bowerrc', '.bowerrc');
        $this->rename($path . '/jshintrc', '.jshintrc');
        $this->rename($path . '/gitignore', '.gitignore');

        $this->copy($templateDir . '/assets', $path . '/resources/assets');
    }

    protected function runBuild($path)
    {
        $this->logger->writeln($this->getFormatter()->format('Initialize...', 'green'));
        chdir($path);
        exec('sh < build.sh');
        $this->logger->writeln('Done! :D');
    }

    protected function showFinalMessage()
    {
        $this->logger->newline();
        $this->logger->writeln('Insert these lines to your template files, I can\'t do this for you: :/');
        $this->logger->newline();

        $initMessage = file_get_contents(__DIR__ . '/../../messages/init.txt');
        $this->logger->info($this->formatter->format($initMessage, 'yellow'));

        $this->logger->writeln('You can run `gulp` for development or `gulp --production` to build.');
    }

    public function execute($path = null)
    {
        if (!$this->checkEnv()) {
            return false;
        }

        $path = $this->getProjectPath($path);
        if (!$this->isLaravel5($path)) {
            throw new Exception('It is not a project of Laravel 5.');
        }
        $this->generateFiles($path);
        $this->runBuild($path);
        $this->showFinalMessage();

        return true;
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
