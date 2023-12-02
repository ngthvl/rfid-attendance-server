<?php


namespace Tamani\RfidTerminal\Traits;


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
}
