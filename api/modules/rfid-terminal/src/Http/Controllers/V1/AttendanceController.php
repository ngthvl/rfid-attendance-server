<?php


namespace Tamani\RfidTerminal\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tamani\RfidTerminal\Models\RfidOutput;
use Tamani\Students\Models\Student;
use Tamani\Students\Notifications\NotifyParentOnDetect;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $uid = $request->input('id');
        $ts = $request->input('ts', time());

        $td = Carbon::createFromTimestamp($ts);

        $out = new RfidOutput();
        $out->student_uid = $uid;
        $out->detection_dt = $td;

        $out->save();

        /** @var Student $student */
//        $student = Student::where('student_id', $uid)->first();
//        $student->notify(new NotifyParentOnDetect($out));

        return $this->respondWithEmptyData();
    }
}
