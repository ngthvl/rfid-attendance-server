<?php


namespace Tamani\RfidTerminal\Http\Controllers\V1;


use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tamani\RfidTerminal\Enums\AllocationTypes;
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

//        $baseLine = (Carbon::now())->sub('seconds', env('ATTENDANCE_INTERVAL_SECS', 60));

//        $outlast = RfidOutput::where('detected_uid', $uid)->where('detection_dt', '<=', $baseLine)->first();

//        if($outlast){
//            return $this->respondWithError('ALREADY_DETECTED', 422, 'Throttled');
//        }

        $allocation = RfidTagAllocation::where('tag_data', $uid)->first();

        if($allocation){
            $td = Carbon::createFromTimestamp($ts);

            $out = new RfidOutput();
            $out->detected_uid = $uid;
            $out->detection_dt = $td;
            $out->date_detected = $td;

            $out->terminal()->associate(auth()->user());

            $out->save();

            $notif = AllocationTypes::TYPES[$allocation->allocation_type]['notification'];
            if($notif){
                $allocation->allocation->notify(new $notif($out));
            }

            return $allocation->allocation;
        }

        throw new \Exception('error');

        return $this->respondWithError('INVALID_ENTRY', 500, 'Unknown Tag');
    }
}
