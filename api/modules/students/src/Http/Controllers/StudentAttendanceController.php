<?php


namespace Tamani\Students\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\QueryBuilder;
use Tamani\Students\Models\Student;

class StudentAttendanceController extends Controller
{
    public function index()
    {
        $qb = QueryBuilder::for(Student::class)->with('attendance')->get();

        return JsonResource::collection($qb);
    }
}
