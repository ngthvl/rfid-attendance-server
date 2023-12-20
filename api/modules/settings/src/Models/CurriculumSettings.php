<?php
namespace Tamani\Settings\Models;

class CurriculumSettings extends \Spatie\LaravelSettings\Settings
{

    public ?string $school_year;

    public static function group(): string
    {
        return 'curriculum_settings';
    }
}
