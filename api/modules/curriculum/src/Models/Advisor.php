<?php


namespace Tamani\Curriculum\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Advisor extends Model
{
    const FILLABLE = [
        'teacher_id',
        'section_id',
        'active_from',
        'active_until',
    ];

    /**
     * Advisor constructor.
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = self::FILLABLE;
        parent::__construct($attributes);
    }

    public function teacher()
    {

    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function getIsActiveAttribute()
    {
        $from = new Carbon($this->active_from);
        $to = new Carbon($this->active_until);

        return $from->isPast() && $to->isFuture();
    }
}
