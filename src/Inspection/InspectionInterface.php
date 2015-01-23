<?php

namespace Inspector\Inspection;

use Inspector\Issue\IssueInterface;

interface InspectionInterface
{
    public function addIssue(IssueInterface $issue);
    public function getIssues();
    public function hasIssues();
    public function getShortName();
}
