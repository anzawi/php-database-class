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


use PHPtricks\Orm\Command\CommandHelpers as Helpers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Drop extends Command
{

    private $dir;
    private $errors = [];
    private $warnings = [];
    private $success = 0;
    /**
     * @var \PHPtricks\Orm\Command\CommandHelpers
     */
    private $_helpers;

    public function __construct(string $name = null)
    {
        parent::__construct($name);
        $this->dir      = config('directories.drop');
        $this->_helpers = new Helpers();
    }

    protected function configure()
    {
        $this
            ->setName('migrate:drop')
            ->setDescription('Drop Tables (Migrations/Drop).');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $classes = glob($this->dir.'*.*');

        foreach ($classes as $class) {
            $obj = null;
            require $class;
            $name = explode('/', $class);
            $name = end($name);
            $name = str_replace('.php', '', $name);
            if ($this->_helpers->isMigrated($name)) {
                continue;
            }
            try {
                $obj = new $name();
                try {
                    $status = $obj->run();
                    if ( ! $status) {
                        $this->warnings[] = $obj->getErrors();
                    } else {
                        $this->success++;
                    }
                    $this->_helpers->setMigrationAsMigrated($name);
                } catch (\Exception $exc) {
                    $output->writeln("<error>Unable to Trigger (run) method in ({$name}) Class.</error>");
                    $this->errors []
                        = "Unable to Trigger (run) method in ({$name}) Class.";
                    continue;
                }
            } catch (\Exception $exception) {
                $output->writeln("<error>Unable to Find ({$name}) Class.</error>");
                $this->errors [] = "Unable to Find ({$name}) Class.";
                continue;
            }
        }

        if ($this->success < 1) {
            $output->writeln('<error>Unable to Migrate!</error>');
        }
        if (count($this->errors)) {
            $output->writeln('<comment>Migrated With Errors</comment>');
            $output->writeln("<error>(".count($this->errors).") Errors</error>");
            foreach ($this->errors as $error) {
                $output->writeln("<error>{$error}</error>");
            }
        }
        if (count($this->warnings)) {
            foreach ($this->warnings as $warning) {
                $output->writeln("<comment>{$warning}</comment>");
            }
        }


        $output->writeln("<info>Drop Tables Successfully.</info>");
        $output->writeln("<info>Success {$this->success}.</info>");
        $output->writeln("<comment>Warnings ".count($this->warnings)
                         .".</comment>");
        $output->writeln("<error>Errors ".count($this->errors).".</error>");


        return Command::SUCCESS;
    }

}