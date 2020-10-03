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

namespace PHPtricks\Orm\Command\Config;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetConfig extends Command
{

    protected function configure()
    {
        $this
            ->setName('config:get')
            ->setDescription('Show your Active Configurations and options.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $provider   = config('default');
        $datatype   = config('fetch');
        $config = config('connections.'.$provider);

        $table = new Table($output);
        $table->setStyle('box');
        $table->setHeaderTitle('Configurations');

        $table->setHeaders([
            'Config',
            'Value',
            'Notes',
        ]);

        $table->addRow([
            'Fetch Data As',
            ($datatype === 2 ? 'Array' : "Object"),
            'you can choice (array or object) other types (FETCH_LAZY, FETCH_NUM,..etc) it might cause problems'
        ]);
        $table->addRow(new TableSeparator());

        foreach ($config as $index => $value) {
            $note = '';

            if ($index == 'driver') {
                $note = 'We Support (MySQL, PostgreSQL, SQLite, MS SQL Server, Oracle Call Interface)';
            }

            $table->addRow([
                getColumnName($index),
                $value,
                $note,
            ]);
            $table->addRow(new TableSeparator());
        }

        $table->render();

        return Command::SUCCESS;
    }

}