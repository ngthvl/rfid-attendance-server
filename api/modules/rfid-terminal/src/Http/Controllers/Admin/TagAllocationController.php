<?php


namespace Tamani\RfidTerminal\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tamani\RfidTerminal\Http\Resources\RfidTagAllocationResource;
use Tamani\RfidTerminal\Models\RfidTagAllocation;

class TagAllocationController extends Controller
{
    public function checkAllocation(Request $request)
    {
        $tagFind = RfidTagAllocation::where('tag_data', $request->input('rfid_data'))
            ->where('allocation_id', '<>' , $request->input('allocation_id'))
            ->where('allocation_type', '<>' , config('rfid_terminal.classmap.' . $request->input('allocation_type')))
            ->first();

        if($tagFind){
            return $this->respondWithError('tag_already_allocated', 400, 'Error: Tag is already allocated');
        }

        return $this->respondWithEmptyData();
    }

    public function allocateTag(Request $request)
    {
        $tagFind = RfidTagAllocation::where('tag_data', $request->input('rfid_data'))
            ->where('allocation_id', $request->input('allocation_id'))
            ->where('allocation_type', config('rfid_terminal.classmap.' . $request->input('allocation_type')))
            ->first();

        $tag = new RfidTagAllocation();
        $tag->tag_data = $request->input('rfid_data');
        $tag->allocation_id = $request->input('allocation_id');
        $tag->allocation_type = config('rfid_terminal.classmap.' . $request->input('allocation_type'));
        $tag->save();

        if($tagFind){
            return $this->respondWithError('tag_already_allocated', 400, 'Error: Tag is already allocated');
        }

        return new RfidTagAllocationResource($tag);
    }
}
