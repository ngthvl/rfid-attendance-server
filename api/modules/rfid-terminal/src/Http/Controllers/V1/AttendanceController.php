<?php


namespace Tamani\RfidTerminal\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tamani\RfidTerminal\Models\RfidOutput;
use Tamani\RfidTerminal\Models\RfidTagAllocation;
use Tamani\Students\Models\Student;
use Tamani\Students\Notifications\NotifyParentOnDetect;

class AttendanceController extends Controller
{
    public function store(Request $request)
    {
        $uid = $request->input('id');
        $ts = $request->input('ts', time());

        $allocation = RfidTagAllocation::where('tag_data', $uid)->first();

        if($allocation){
            $td = Carbon::createFromTimestamp($ts);

            $out = new RfidOutput();
            $out->student_uid = $uid;
            $out->detection_dt = $td;

            $out->save();

            $allocation->allocation->notify(new NotifyParentOnDetect($out));

            return $allocation->allocation;
        }

        return $this->respondWithEmptyData();
    }
}
