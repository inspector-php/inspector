<?php

namespace Inspector;

class Link
{
    private $url;
    private $label;
    
    public function __construct($url, $label)
    {
        $this->url = $url;
        $this->label = $label;
    }
    
    public function getUrl()
    {
        return (string)$this->url;
    }
    
    public function getLabel()
    {
        return (string)$this->label;
    }
}
