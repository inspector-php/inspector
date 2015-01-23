<?php

namespace Inspector;

use Inspector\Inspection\InspectionInterface;

interface InspectorInterface
{
    public function getInspections();
    public function addInspection(InspectionInterface $inspection);
    public function runInspection(InspectionInterface $inspection);
    public function run();
}
