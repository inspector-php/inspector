<?php

namespace Inspector\Formatter;

use Inspector\InspectorInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleFormatter
{
    private $output = OutputInterface::VERBOSITY_NORMAL;

    public function format(InspectorInterface $inspector)
    {
        $o = '';
        foreach ($inspector->getInspections() as $inspection) {
            if ($inspection->hasIssues()) {
                $o .= " <error>✘ " . $inspection->getShortName() . "</error>\n";
                $o .= $this->getDetails($inspection);
            } else {
                $o .= " <info>✓ " . $inspection->getShortName() . "</info>\n";
            }
        }
        $o .= "\n";
        return $o;
    }

    private function getDetails($inspection)
    {
        $o = '';
        if ($this->output->isVerbose()) {
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
        if ($this->output->isVeryVerbose()) {
            $o .= "       <comment>Solution:</comment> " . $issue->getSolution() . "\n";
            foreach ($issue->getLinks() as $link) {
                $o .= "       <comment>Link:</comment> [" . $link->getLabel() . "](" . $link->getUrl() . ")\n";
            }
        }
        return $o;
    }

    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
        return $this;
    }
}
