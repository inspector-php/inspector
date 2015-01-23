<?php

namespace Inspector\Inspection;

use Inspector\Issue\IssueInterface;

class Inspection implements InspectionInterface
{
    private $className;
    private $methodName;
    private $issues;
    
    public function __construct($className, $methodName)
    {
        $this->className = $className;
        $this->methodName = $methodName;
    }
    
    public function getClassName()
    {
        return $this->className;
    }
    
    public function getMethodName()
    {
        return $this->methodName;
    }
    
    public function addIssue(IssueInterface $issue)
    {
        $this->issues[] = $issue;
    }
    
    public function getIssues()
    {
        return $this->issues;
    }
    
    public function hasIssues()
    {
        if (count($this->issues)>0) {
            return true;
        }
        return false;
    }
    
    public function getShortName()
    {
        return $this->getClassName() . '::' . $this->getMethodName();
    }
}
