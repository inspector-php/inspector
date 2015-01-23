<?php

namespace Inspector;

use Inspector\Issue\IssueInterface;

interface InspectorInterface
{
    public function addIssue(IssueInterface $issue);
}
