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
}
