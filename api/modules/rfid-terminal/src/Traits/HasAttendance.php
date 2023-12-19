<?php


namespace Tamani\RfidTerminal\Traits;


use Tamani\RfidTerminal\Models\RfidOutput;

trait HasAttendance
{
    public function attendance()
    {
        return $this->morphMany(RfidOutput::class, '');
    }
}
