<?php 

namespace Example\Inspection;

use Inspector\Inspection\InspectionInterface;
use Inspector\Inspection\AbstractPdoInspection;
use Example\Issue\EmptyPasswordIssue;

class DatabaseUserInspection extends AbstractPdoInspection
{
    public function inspectPasswords(InspectionInterface $inspection)
    {
        $rows = $this->queryRows("SELECT * FROM user_entry WHERE isnull(password) ORDER BY id");
        foreach ($rows as $row) {
            $issue = new EmptyPasswordIssue();
            $issue->setData('userid', $row['id']);
            $issue->setData('username', $row['username']);
            $issue->setData('displayname', trim($row['firstname'] . ' ' . $row['lastname']));
            $inspection->addIssue($issue);
        }
    }
}
