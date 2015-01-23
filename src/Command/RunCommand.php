<?php

namespace Inspector\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use LinkORB\Component\Database\Database;
use Inspector\Inspector;
use Inspector\Output as InspectionOutput;
use Inspector\Loader\YamlLoader;
use Inspector\Formatter\ConsoleFormatter;

class RunCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('inspector:run')
            ->setDescription('Run inspections from yml file')
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'Filename'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        //$dbname = $input->getArgument('dbname');
        $filename  = $input->getArgument('filename');
        
        $container = array();
        //$container['db'] = Database::get($dbname);
        
        $inspector = new Inspector($container);
        
        $output->write("<info>Inspector: running [$filename]</info>\n");
        
        $loader = new YamlLoader();
        $loader->load($inspector, $filename);
        
        $inspector->run();
        
        $formatter = new ConsoleFormatter();
        $output->write($formatter->format($inspector));
    }
}
