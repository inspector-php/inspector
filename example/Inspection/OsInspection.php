<?php 

namespace Example\Inspection;

use Inspector\Inspection\Inspection;
use Example\Issue\NoHostnameFileIssue;

class OsInspection
{
    public function inspectExample()
    {
        return true;
    }
    public function inspectHostsFile(Inspection $inspection)
    {
        if (!file_exists('/etc/hosts')) {
            $issue = new NoHostsFileIssue();
            $inspection->addIssue($issue);
        }
    }
}
