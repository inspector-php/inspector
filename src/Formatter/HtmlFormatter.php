<?php

namespace Inspector\Formatter;

use Inspector\Output;

class HtmlFormatter
{
    public function format(Output $output)
    {
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
