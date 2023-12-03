<?php

namespace Tamani\RfidTerminal\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Tamani\RfidTerminal\Http\Requests\StoreAuthRequest;
use Tamani\RfidTerminal\Models\RfidTerminal;

class AuthController extends Controller
{
    public function store(StoreAuthRequest $request): \Illuminate\Http\JsonResponse
    {
        $deviceId = $request->input('device-id');
        $deviceIp = $request->server->get('REMOTE_ADDR');

        $user = RfidTerminal::where('ip_address', $deviceIp)->first();

        if($user && $user->id != $deviceId){
            $user->delete();
        }

        if(!$user){
            RfidTerminal::create([
                'id' => $deviceId,
                'device_name' => 'TERM-' . Str::random(3),
                'ip_address' => $deviceIp,
                'devices_status' => []
            ]);
        }

        return $this->respondWithEmptyData();
    }

    public function refresh()
    {
        /** @var RfidTerminal $user */
        $user = auth()->user();

        $token = $user->createToken('rfid_terminal_access_client')->accessToken;

        return $this->respondWithToken($token, $user);
    }
}
