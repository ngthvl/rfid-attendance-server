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
        $tags = $this->tagList();
        return $this->detections()
            ->selectRaw('min(rfid_outputs.detection_dt) as detection_dt')
            ->orderBy('rfid_outputs.detection_dt')
            ->groupBy(DB::raw('DATE(rfid_outputs.detection_dt)'))
            ->groupBy(DB::raw('rfid_tag_allocations.allocation_id'))
            ;
    }
}
