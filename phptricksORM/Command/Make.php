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

class Make extends Command
{

    protected function configure()
    {
        $this
            ->setName('migrate:make')
            ->setDescription('Generate Migration Class (file)')
            ->addArgument('class', InputArgument::REQUIRED,
                'Class Name | File Name')
            ->addArgument('type', InputArgument::REQUIRED,
                'choose class type [create, alter, drop].')
            ->addOption('table', 't', InputOption::VALUE_OPTIONAL, 'table name',
                'change_this_to_table_name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name   = $input->getArgument('class');
        $action = strtolower($input->getArgument('type'));
        $table  = str_replace('=', '', $input->getOption('table'));

        switch ($action) {
            case 'create':
                $this->generate('create', $name, $table);
                break;
            case 'alter':
                $this->generate('alter', $name, $table);
                break;
            case 'drop':
                $this->generate('drop', $name, $table);
                break;

            default:
                $output->writeln("<error>[{$action}] is not command!, its must be one of (create, alter, drop).</error>");

                return Command::FAILURE;
        }

        $output->writeln("<info>Migration Created Successfully</info>");
        $output->writeln("===========");
        $output->writeln("");

        return Command::SUCCESS;
    }

    /**
     * @param  string  $name
     *
     * @return int
     */
    private function generate(string $type, string $name, string $table)
    {
        $path = rtrim(config("directories.{$type}"), '/');
        $path = $path."/$name.php";
        @mkdir(dirname($path), 0755, true);
        $tempContent = file_get_contents(__DIR__."/Templates/{$type}.template");
        $tempContent = str_replace('{%CLASS_NAME%}', $name, $tempContent);
        $tempContent = str_replace('{%TABLE_NAME%}', $table, $tempContent);
        file_put_contents($path, $tempContent);

        return Command::SUCCESS;
    }

}