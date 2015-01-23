<?php 

namespace Example\Inspection;

use Inspector\Inspection\InspectionInterface;
use Example\Issue\NoHostnameFileIssue;

class OsInspection
{
    public function inspectExample()
    {
        return true;
    }
    public function inspectHostsFile(InspectionInterface $inspection)
    {
        if (!file_exists('/etc/hosts')) {
            $issue = new NoHostsFileIssue();
            $inspection->addIssue($issue);
        }
    }
}
