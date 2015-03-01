<?php 

namespace Example\Issue;

use Inspector\Inspection\Inspection;
use Inspector\Issue\BaseIssue;
use Inspector\Link;

class EmptyPasswordIssue extends BaseIssue
{
    public function getLinks()
    {
        $links = array();
        $links[] = new Link('/admin/user/' . $this->data['userid'] . "/password", "Set password");
        return $links;
    }
}
