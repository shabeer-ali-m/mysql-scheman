<?php
require __DIR__ . '/../vendor/autoload.php';

use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;
use MysqlScheman\MysqlSchemanCli;

class Minimal extends CLI
{
    // register options and arguments
    protected function setup(Options $options)
    {
        $options->setHelp('MysqlScheman a MySQL schema synchronization utility tool.');
        $options->registerOption('version', 'Version', 'v');
        $options->registerOption('config', 'Database config file', 'c', 'config');
        $options->registerOption('export', 'Export database to a file', 'e', 'export');
        $options->registerOption('sync', 'Sync the file with database', 's', 'sync');
    }

    protected function main(Options $options)
    {
        try {
            $scheman = new MysqlSchemanCli;

            if ($options->getOpt('config')) {
                $scheman->setConfig($options->getOpt('config'));
            }

            if ($options->getOpt('version')) {
                $this->info(MysqlSchemanCli::version);
            } else if ($options->getOpt('export')) {
                $scheman->export($options->getOpt('export'));
                $this->info('Export completed successfully! File : '. $options->getOpt('export'));
            } else if ($options->getOpt('sync')) {
                $scheman->sync2db($options->getOpt('sync'));
                $this->info('Sync successfully completed!');
            } else {
                echo $options->help();
            }
        }
        catch(Exception $e) {
            $this->error($e->getMessage() . PHP_EOL);
        }
    }
}
$cli = new Minimal();
$cli->run();