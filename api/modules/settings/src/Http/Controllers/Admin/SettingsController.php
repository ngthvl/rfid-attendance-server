<?php
namespace Tamani\Settings\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Tamani\Settings\Http\Requests\UpdateSettingsRequest;
use Tamani\Settings\Models\CurriculumSettings;

class SettingsController extends Controller
{
    public function index(
        CurriculumSettings $curriculum
    )
    {
        return new JsonResource([
            CurriculumSettings::group() => $curriculum->toArray()
        ]);
    }

    public function store(UpdateSettingsRequest $request)
    {
        $curriculum = app(CurriculumSettings::class);

        $curriculum->school_year = $request->validated('curriculum_settings.school_year');

        $curriculum->save();

        return $this->respondWithEmptyData();
    }
}
