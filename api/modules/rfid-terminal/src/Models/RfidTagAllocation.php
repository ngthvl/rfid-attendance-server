<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfidTagAllocation extends Model
{
    use HasFactory;

    public function allocation(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('allocation');
    }
}
