<?php


namespace Tamani\Curriculum\Models;


use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    const FILLABLE = [
        'section_name',
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable = self::FILLABLE;
        parent::__construct($attributes);
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class, 'education_level_id');
    }
}
