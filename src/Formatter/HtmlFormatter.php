<?php

namespace Inspector\Formatter;

use Inspector\InspectorInterface;

class HtmlFormatter
{
    public function format(InspectorInterface $inspector)
    {
        // TODO: Update based on new hierarchy, check ConsoleFormatter for working example
        $o = '<table class="table">';
        $o .= '<tr><th>Level</th><th>Details</th></tr>';
        foreach ($output->getIssues() as $issue) {
            $o .= '<tr><td>' . $issue->getLevelString() . '</td><td>';
            $o .= '<b>' . $issue->getSubject() . '</b><br />' . $issue->getMessage();
            $o .= '</td></tr>';
        }
        $o .= '</table>';
        
        $o .= "Checks: <b>" . $output->getCheckCount() . "</b> Issues: <b>" . $output->getIssueCount() . "</b><br />";
        $o .= "\n";
        return $o;
    }
}
