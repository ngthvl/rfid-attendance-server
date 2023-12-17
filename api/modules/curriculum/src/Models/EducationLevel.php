<?php


namespace Tamani\Curriculum\Models;


use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    const FILLABLE = [
        'education_level_name',
    ];

    /**
     * Advisor constructor.
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = self::FILLABLE;
        parent::__construct($attributes);
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'education_level_id');
    }
}
