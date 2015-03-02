<?php

namespace Inspector\Loader;

use Inspector\Inspector;
use Inspector\Inspection\Inspection;
use RuntimeException;
use ReflectionClass;

class SingleInspectorLoader
{
    public function load(Inspector $inspector, $inspectorName)
    {
        if (substr($inspectorName, -10) == 'Inspection') {
            $className = $inspectorName;
        } else {
            $className = $inspectorName . 'Inspection';
        }
        
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
