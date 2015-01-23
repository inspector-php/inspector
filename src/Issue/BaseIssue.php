<?php

namespace Inspector\Issue;

use ReflectionClass;
use RuntimeException;

abstract class BaseIssue
{
    protected $subject = "Undefined subject";
    protected $message;
    
    private $template = array();
    
    private function getTemplate($version)
    {
        if (!isset($this->template[$version])) {
            $className = get_class($this);
            $c = new ReflectionClass($className);
            $templateFilename = $c->getFilename();
            $templateFilename = str_replace('.php', '.json', $templateFilename);
            if (!file_exists($templateFilename)) {
                throw new RuntimeException("Template file not found: " . $templateFilename);
            }
            $json = file_get_contents($templateFilename);
            $this->template[$version] = json_decode($json, true);
        }
        return $this->template[$version];
    }
    
    public function getSubject($version = '')
    {
        $template = $this->getTemplate($version);
        $subject = $template['subject'];
        $subject = $this->applyData($subject);
        
        return (string)$subject;
    }

    private function applyData($text)
    {
        foreach ($this->data as $key=>$value) {
            $text = str_replace("{{" . $key . "}}", $value, $text);
        }
        return $text;
    }
    
    public function getDescription($version = '')
    {
        $template = $this->getTemplate($version);
        $text = (string)$template['description'];
        $text = $this->applyData($text);
        return $text;
    }
    
    public function getSolution($version = '')
    {
        $template = $this->getTemplate($version);
        $text = (string)$template['solution'];
        $text = $this->applyData($text);
        return $text;
    }
    
    protected $data = array();
    
    public function setData($key, $value)
    {
        return $this->data[$key] = $value;
    }
    
    public function hasData($key)
    {
        if (isset($this->data[$key])) {
            return true;
        }
        return false;
    }
}
