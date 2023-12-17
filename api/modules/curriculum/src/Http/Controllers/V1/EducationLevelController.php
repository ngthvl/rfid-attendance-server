<?php

namespace Tamani\Curriculum\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\QueryBuilder;
use Tamani\Curriculum\Http\Resources\EducationLevelResource;
use Tamani\Curriculum\Models\EducationLevel;

class EducationLevelController extends Controller
{
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $qb = QueryBuilder::for(EducationLevel::class)->get();

        return EducationLevelResource::collection($qb);
    }

    public function store(Request $request)
    {
        $educLevel = new EducationLevel($request->only(EducationLevel::FILLABLE));

        $educLevel->save();

        return new EducationLevelResource($educLevel);
    }
}
