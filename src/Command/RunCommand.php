<?php

namespace Inspector\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Inspector\Inspector;
use Inspector\Output as InspectionOutput;
use Inspector\Loader\YamlLoader;
use Inspector\Loader\SingleInspectorLoader;
use Inspector\Formatter\ConsoleFormatter;
use PDO;
use PDOException;
use RuntimeException;

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
            )
            ->addOption(
                'pdoconfig',
                null,
                InputOption::VALUE_REQUIRED,
                'Path to a .ini file containing pdo connection configuration'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $filename  = $input->getArgument('filename');
        $pdoconfigfilename = $input->getOption('pdoconfig');
        
        $container = array();
        
        $output->write("<info>Inspector: running [$filename]</info>\n");
        if ($pdoconfigfilename) {
            $output->write("<info>PDO config filename: [$pdoconfigfilename]</info>\n");
            $container['pdo'] = $this->getPdoFromConfigFile($pdoconfigfilename);
        } else {
            $output->write("<info>No PDO config file provided</info>\n");
        }
        
        $inspector = new Inspector($container);
        
        $this->load($inspector, $filename);
        
        $inspector->run();
        
        $formatter = new ConsoleFormatter();
        $formatter->setOutput($output);
        $output->write($formatter->format($inspector));
    }

    private function load(Inspector $inspector, $filename)
    {
        if (file_exists($filename)) {
            $this->loadYaml($inspector, $filename);
        } else {
            $this->loadSingleInspector($inspector, $filename);
        }
    }

    private function loadYaml(Inspector $inspector, $filename)
    {
        $loader = new YamlLoader();
        $loader->load($inspector, $filename);
    }

    private function loadSingleInspector(Inspector $inspector, $inspectorName)
    {
        $loader = new SingleInspectorLoader();
        $loader->load($inspector, $inspectorName);
    }
    
    private function getPdoFromConfigFile($filename)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException("File not found: " . $filename);
        }
        $config = parse_ini_file($filename);
        return $this->getPdoFromConfig($config);
    }
    
    private function getPdoFromConfig($config)
    {
        if (!isset($config['server'])) {
            throw new RuntimeException("server not defined in config");
        }
        
        if (!isset($config['name'])) {
            throw new RuntimeException("name of the database not defined in config");
        }
        
        if (!isset($config['username']) || !isset($config['password'])) {
            throw new RuntimeException("username and/or password not defined in config");
        }
        
        $driver = 'mysql';
        if (isset($config['driver'])) {
            $driver = $config['driver'];
        }
        
        try {
            $pdo = new PDO($driver . ':host=' . $config['server'] . ';dbname=' . $config['name'], $config['username'], $config['password']);
        } catch (PDOException $e) {
            throw new RuntimeException("Database connection failed: " . $e->getMessage());
        }
        return $pdo;
    }
}
