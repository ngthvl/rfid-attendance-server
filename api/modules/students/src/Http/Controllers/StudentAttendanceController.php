<?php


namespace Tamani\Students\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Tamani\RfidTerminal\Models\RfidOutput;
use Tamani\Students\Models\Student;

/**
 * Class StudentAttendanceController
 * @package Tamani\Students\Http\Controllers
 */
class StudentAttendanceController extends Controller
{
    /**
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $qb = QueryBuilder::for(Student::class)
            ->with('attendance', function($query){
                if(Request::has('filter.from_date')){
                    $query->whereDate('date_detected', '>=', Request::input('filter.from_date'));
                }

                if(Request::has('filter.to_date')){
                    $query->whereDate('date_detected', '<=', Request::input('filter.to_date'));
                }
            })
            ->allowedFilters([
                'section_id',
                'education_level_id',
                AllowedFilter::callback('from_date', function(Builder $query, $value){}),
                AllowedFilter::callback('to_date', function(Builder $query, $value){}),
            ])
            ->get();

        return JsonResource::collection($qb);
    }

    /**
     * @param string $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function dailyAttendance(string $id): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $student = Student::find($id);
        $qb = QueryBuilder::for(RfidOutput::class)
            ->selectRaw('min(detection_dt) as time_in, max(detection_dt) as time_out, date_detected as attendance_dt')
            ->whereIn('detected_uid', $student->tagList())
            ->orderBy('date_detected')
            ->groupBy('date_detected')
            ->paginate();

        return JsonResource::collection($qb);
    }
}
