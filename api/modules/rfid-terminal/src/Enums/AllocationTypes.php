<?php


namespace Tamani\RfidTerminal\Enums;


use Tamani\Students\Models\Student;
use Tamani\Students\Notifications\NotifyParentOnDetect;

class AllocationTypes
{
    const TYPES = [
        Student::class => [
            'notification' => NotifyParentOnDetect::class
        ]
    ];
}
