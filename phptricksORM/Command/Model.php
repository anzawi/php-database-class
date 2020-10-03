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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class Model extends Command
{

    /**
     * @var string
     */
    private $_dir;


    public function __construct(string $name = null)
    {
        parent::__construct($name);
        $this->_dir = config('directories.models');
    }

    protected function configure()
    {
        $this
            ->setName('model')
            ->setDescription('Create New Model Class.')
            ->addArgument('class', InputArgument::REQUIRED,
                'Class Name | File Name')
            ->addOption('table', 't', InputOption::VALUE_OPTIONAL, 'table name',
                'change_this_to_table_name');
    }

    /**
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table  = str_replace('=', '', $input->getOption('table'));
        $name   = $input->getArgument('class');
        $path = rtrim($this->_dir, '/');
        $path = $path."/$name.php";
        @mkdir(dirname($path), 0755, true);
        $tempContent = file_get_contents(__DIR__."/Templates/model.template");
        $tempContent = str_replace('{%CLASS_NAME%}', $name, $tempContent);
        $tempContent = str_replace('{%TABLE_NAME%}', $table, $tempContent);
        file_put_contents($path, $tempContent);

        $output->writeln("<info>Model Created Successfully</info>");
        $output->writeln("===========");
        $output->writeln("");

        return Command::SUCCESS;
    }

}