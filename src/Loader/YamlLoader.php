<?php

namespace Inspector\Loader;

use Symfony\Component\Yaml\Parser as YamlParser;
use Inspector\Inspector;
use Inspector\Inspection\Inspection;
use RuntimeException;
use ReflectionClass;

class YamlLoader
{
    public function load(Inspector $inspector, $filename)
    {
        if (!file_exists($filename)) {
            throw new RuntimeException("File not found: " . $filename);
        }
        $yamlparser = new YamlParser();
        $data = $yamlparser->parse(file_get_contents($filename));

        if (isset($data['include'])) {
            foreach ($data['include'] as $includeName) {
                $path = dirname($filename);
                $this->load($inspector, $path . '/' . $includeName);
            }
        }
        
        if (isset($data['classes'])) {
            foreach ($data['classes'] as $className) {
                $reflector = new ReflectionClass($className);
                $method = $reflector->getConstructor();
                
                foreach ($reflector->getMethods() as $method) {
                    $methodName = $method->getName();
                    if (substr($methodName, 0, 7) == 'inspect') {
                        $inspection = new Inspection($className, $methodName);
                        $inspector->addInspection($inspection);
                    }
                }
            }
        }
    }
}
