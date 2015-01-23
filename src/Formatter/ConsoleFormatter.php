<?php

namespace Inspector\Formatter;

use Inspector\Inspector;

class ConsoleFormatter
{
    public function format(Inspector $inspector)
    {
        $o = '';
        foreach ($inspector->getInspections() as $inspection) {
            if ($inspection->hasIssues()) {
                $o .= " <error>✘ " . $inspection->getShortName() . "</error>\n";
                foreach ($inspection->getIssues() as $issue) {
                    $o .= "  - <comment>" . $issue->getSubject() . "</comment>\n";
                }
            } else {
                $o .= " <info>✓ " . $inspection->getShortName() . "</info>\n";
            }
        }
        
        /*
        $o .= "Checks: " . $output->getCheckCount() . " Issues: ";
        if ($output->getIssueCount()>0) {
            $o.= "<error>" . $output->getIssueCount() . "</error>";
        } else {
            $o.= "<info>" . $output->getIssueCount() . "</info>";
        }
        */
        $o .= "\n";
        return $o;
    }
}
