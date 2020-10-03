<?php
/**
 * *
 *  * please don't remove this comment block
 *  *
 *  * @author phptricks Team - Mohammad Anzawi
 *  * @author_uri https://phptricks.org
 *  * @uri https://github.com/anzawi/php-database-class
 *  * @version 5.0.0
 *  * @licence MIT -> https://opensource.org/licenses/MIT
 *  * @package PHPtricks\Orm
 *
 */

namespace PHPtricks\Orm\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Migrate extends Command
{

    protected function configure()
    {
        $this
            ->setName('migrate')
            ->setDescription('Migrate All Migration inside (Migration Folder [create/*.php, alter/*.php, drop/*.php])');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $createCommand = $this->getApplication()->find('migrate:create');
        $createCommand->run($input, $output);

        $alterCommand = $this->getApplication()->find('migrate:alter');
        $alterCommand->run($input, $output);

        $dropCommand = $this->getApplication()->find('migrate:drop');
        $dropCommand->run($input, $output);

        return Command::SUCCESS;
    }

}