<?php

namespace Inspector\Formatter;

use Inspector\InspectorInterface;

class ConsoleFormatter
{
    private $verboseLevel = 0;

    public function format(InspectorInterface $inspector)
    {
        $o = '';
        foreach ($inspector->getInspections() as $inspection) {
            if ($inspection->hasIssues()) {
                $o .= " <error>✘ " . $inspection->getShortName() . "</error>\n";
                /*
                foreach ($inspection->getIssues() as $issue) {
                    $o .= "  - <comment>" . $issue->getSubject() . "</comment>\n";
                    $o .= "       <comment>Details:</comment> " . $issue->getDescription() . "\n";
                    $o .= "       <comment>Solution:</comment> " . $issue->getSolution() . "\n";
                    foreach ($issue->getLinks() as $link) {
                        $o .= "       <comment>Link:</comment> [" . $link->getLabel() . "](" . $link->getUrl() . ")\n";
                    }
                }
                */
                $o .= $this->getDetails($inspection);
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

    private function getDetails($inspection)
    {
        $o = '';
        if ($this->verboseLevel > 0) {
            foreach ($inspection->getIssues() as $issue) {
                $o .= "  - <comment>" . $issue->getSubject() . "</comment>\n";
                $o .= "       <comment>Details:</comment> " . $issue->getDescription() . "\n";
                $o .= $this->getSolution($issue);
            }
        }
        return $o;
    }

    private function getSolution($issue)
    {
        $o = '';
        if ($this->verboseLevel > 1) {
            $o .= "       <comment>Solution:</comment> " . $issue->getSolution() . "\n";
            foreach ($issue->getLinks() as $link) {
                $o .= "       <comment>Link:</comment> [" . $link->getLabel() . "](" . $link->getUrl() . ")\n";
            }
        }
        return $o;
    }

    public function setVerboseLevel($level)
    {
        $this->verboseLevel = (int)$level;
        return $this;
    }
}
