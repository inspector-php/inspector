<?php

namespace Inspector;

use Inspector\Inspection\InspectionInterface;

use ReflectionClass;
use RuntimeException;

class Inspector implements InspectorInterface
{
    private $inspections = array();
    private $container;
    
    public function __construct($container)
    {
        $this->container = $container;
    }
    
    public function addInspection(InspectionInterface $inspection)
    {
        $this->inspections[] = $inspection;
    }
    public function getInspections()
    {
        return $this->inspections;
    }
    
    public function runInspection(InspectionInterface $inspection)
    {
        $className = $inspection->getClassName();
        $methodName = $inspection->getMethodName();
        
        $reflector = new ReflectionClass($className);
        $method = $reflector->getConstructor();
        $arguments = array();
        
        // Inject requested constructor arguments
        if ($method) {
            foreach ($method->getParameters() as $p) {
                if (!isset($this->container[$p->getName()])) {
                    throw new RuntimeException("Requested parameter not defined in container: " . $p->getName());
                }
                $arguments[] = $this->container[$p->getName()];
            }
        }
        $instance = $reflector->newInstanceArgs($arguments);
        
        foreach ($reflector->getMethods() as $method) {
            if ($method->getName()==$methodName) {
                if ($method->isPublic()) {
                    $instance->$methodName($inspection);
                }
            }
        }
    }
    
    public function run()
    {
        foreach ($this->inspections as $inspection) {
            $this->runInspection($inspection);
        }
    }
}
