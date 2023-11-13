<?php


namespace Tamani\RfidTerminal\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tamani\RfidTerminal\Models\RfidOutput;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $uid = $request->input('uid');
        $ts = $request->input('ts', time());

        $td = Carbon::createFromTimestamp($ts);

        $out = new RfidOutput();
        $out->student_uid = $uid;
        $out->detection_dt = $td;

        $out->save();

        return $this->respondWithEmptyData();
    }
}
