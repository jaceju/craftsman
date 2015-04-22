<?php

namespace App\Command;

use App\Application;
use CLIFramework\Command;
use Exception;
use RuntimeException;

class SelfUpdateCommand extends Command
{
    public function brief()
    {
        return 'Updates craftsman.phar to the latest version';
    }

    public function options($opts)
    {
        $opts->add('major', 'Lock to current major version');
        $opts->add('pre', 'Allow pre-releases');
    }

    public function execute($version = '')
    {
        global $argv;
        $script = realpath($argv[0]);

        if (!is_writable($script)) {
            throw new \Exception("$script is not writable.");
        }

        // fetch new version
        $this->logger->info("Updating $script...");

        $pharFile = strtolower(Application::NAME);
        $pharFile .= ('' !== $version) ? '-' . $version : '';
        $pharFile .= '.phar';
        list($vendor, $repository) = explode('/', Application::REPOSITORY);
        $url = sprintf('http://%s.github.io/%s/downloads/%s', $vendor, $repository, $pharFile);
        // $subject = shell_exec('curl -s -I ' . $url);
        // $matches = [];
        // if (preg_match('/Location: (.*)/', $subject, $matches)) {
        //     $url = $matches[1];
        // }
        $this->logger->debug($url);
        $code = system("curl -# -L $url > $script");
        if(!($code == 0)) {
            throw new RuntimeException('Update Failed', 1);
        }

        $this->logger->info('Version updated.');
        system($script . ' --version');
    }
}
