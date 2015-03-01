<?php

namespace Inspector\Inspection;

class DoubleValue
{
    private $value;
    private $keys = array();
    
    public function __construct($value, $keys = array())
    {
        $this->value = $value;
        $this->keys = $keys;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function getKeys()
    {
        return $this->keys;
    }

    public function getCount()
    {
        return count($this->keys);
    }
}
