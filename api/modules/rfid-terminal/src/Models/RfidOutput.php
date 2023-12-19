<?php

namespace Tamani\RfidTerminal\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RfidOutput extends Model
{
    use HasFactory;

    const FILLABLE = [
        'detected_uid',
        'detection_dt',
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable = self::FILLABLE;
        parent::__construct($attributes);
    }

    public function allocation()
    {
        return $this->belongsTo(RfidTagAllocation::class, 'detected_uid', 'tag_data');
    }

    public function allocated()
    {
        return $this->allocation->allocation();
    }

    public function terminal()
    {
        return $this->belongsTo(RfidTerminal::class, 'terminal_id');
    }
}
