<?php


namespace Tamani\RfidTerminal\Http\Controllers\Admin;


use Spatie\QueryBuilder\QueryBuilder;
use Tamani\RfidTerminal\Http\Resources\RfidTagDetectionResource;
use Tamani\RfidTerminal\Models\RfidOutput;

class RfidTagDetectionController
{
    public function index()
    {
        $qb = QueryBuilder::for(RfidOutput::class)->with('allocation')->paginate(20);

        return RfidTagDetectionResource::collection($qb);
    }
}
