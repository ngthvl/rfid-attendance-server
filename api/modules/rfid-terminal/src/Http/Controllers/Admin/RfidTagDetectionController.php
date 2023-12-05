<?php


namespace Tamani\RfidTerminal\Http\Controllers\Admin;


use Spatie\QueryBuilder\QueryBuilder;
use Tamani\RfidTerminal\Models\RfidOutput;

class RfidTagDetectionController
{
    public function index()
    {
        $qb = QueryBuilder::for(RfidOutput::class)->count();

        return $qb;
    }
}
