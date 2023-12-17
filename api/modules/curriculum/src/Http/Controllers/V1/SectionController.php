<?php


namespace Tamani\Curriculum\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Tamani\Curriculum\Http\Resources\SectionResource;
use Tamani\Curriculum\Models\EducationLevel;
use Tamani\Curriculum\Models\Section;

class SectionController extends Controller
{
    public function index()
    {
        $qb = QueryBuilder::for(Section::class)->get();

        return SectionResource::collection($qb);
    }

    public function store(Request $request)
    {
        $educLevel = EducationLevel::find($request->input('education_level_id'));

        if(!$educLevel){
            return $this->respondWithError('EDUCATION_LEVEL_NOT_FOUND', 404, 'Invalid Education Level');
        }

        $sec = new Section($request->only(Section::FILLABLE));
        $sec->educationLevel()->associate($educLevel);

        $sec->save();

        return new SectionResource($sec);
    }
}
