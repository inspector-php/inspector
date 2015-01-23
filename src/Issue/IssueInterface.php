<?php

namespace Inspector\Issue;

interface IssueInterface
{
    public function getSubject();
    public function getDescription();
    public function getSolution();
    public function setData($key, $value);
    public function hasData($key);
    public function getLinks();
}
