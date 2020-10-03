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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Clear extends Command
{

    protected function configure()
    {
        $this
            ->setName('migrate:clear')
            ->setDescription('Clear Cache and mark all migrations classes as un-migrated')
            ->addOption('delete', 'd', InputOption::VALUE_OPTIONAL,
                'delete cache file and directory',
                false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $delete = $input->getOption('delete');
        if ( ! is_bool($delete)) {
            $output->writeln('<error>delete flag must be boolean :</error>');
            $output->writeln('<error>false => (default) just clear cache file content and keep directory</error>');
            $output->writeln('<error>true  => delete cache directory</error>');
            $output->writeln('=<=><=><=><=><=><=><=><=><=><=><=><=>=');

            return Command::FAILURE;
        }

        $helper = new CommandHelpers();
        $helper->clear($delete);

        return Command::SUCCESS;
    }

}