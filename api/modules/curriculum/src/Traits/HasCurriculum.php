<?php
namespace Tamani\Curriculum\Traits;

use Tamani\Curriculum\Models\EducationLevel;
use Tamani\Curriculum\Models\Section;

trait HasCurriculum
{
    public function level()
    {
        return $this->belongsTo(EducationLevel::class, 'education_level_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
