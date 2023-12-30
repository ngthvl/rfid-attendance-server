<?php


namespace Tamani\RfidTerminal\Traits;


use Illuminate\Support\Facades\DB;
use Tamani\RfidTerminal\Models\RfidOutput;
use Tamani\RfidTerminal\Models\RfidTagAllocation;

trait HasTagAllocation
{
    public function allocatedRfidTags(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(RfidTagAllocation::class, 'allocation');
    }

    public function activeRfidTag()
    {
        return $this->morphMany(RfidTagAllocation::class, 'allocation')->orderBy('created_at', 'desc')->first();
    }

    public function tagList(): array
    {
        return array_column($this->allocatedRfidTags->toArray(), 'tag_data');
    }

    public function detections()
    {
        return $this->hasManyThrough(
            RfidOutput::class,
            RfidTagAllocation::class,
            'allocation_id',
            'detected_uid',
            'id',
            'tag_data'
        );
    }

    public function attendance()
    {
        return $this->detections()
            ->selectRaw('date_detected as detection_dt')
            ->orderBy('date_detected')
            ->groupBy('date_detected')
            ->groupBy(DB::raw('rfid_tag_allocations.allocation_id'))
            ;
    }
}
