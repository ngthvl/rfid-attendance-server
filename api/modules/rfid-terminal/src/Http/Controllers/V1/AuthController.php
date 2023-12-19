<?php

namespace Tamani\RfidTerminal\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Tamani\RfidTerminal\Http\Requests\RefreshTokenRequest;
use Tamani\RfidTerminal\Http\Requests\StoreAuthRequest;
use Tamani\RfidTerminal\Models\RfidTerminal;

class AuthController extends Controller
{
    public function store(StoreAuthRequest $request): \Illuminate\Http\JsonResponse
    {
        $deviceId = $request->input('device-id');
        $deviceName = $request->input('device-name');
        $deviceIp = $request->server->get('REMOTE_ADDR');

        $user = RfidTerminal::where('ip_address', $deviceIp)->first();

        if($user && $user->terminal_id != $deviceId){
            $user->update([
                'terminal_id' => $deviceId,
                'device_name' => $deviceName,
            ]);
        }

        if(!$user){
            RfidTerminal::create([
                'terminal_id' => $deviceId,
                'device_name' => $deviceName,
                'ip_address' => $deviceIp,
                'devices_status' => []
            ]);
        }

        return $this->respondWithEmptyData();
    }

    public function refresh(RefreshTokenRequest $request)
    {
        $deviceIp = $request->server->get('REMOTE_ADDR');
        $deviceId = $request->input('device-id');
        $password = $request->input('password');

        /** @var RfidTerminal $user */
        $user = RfidTerminal::where('id', $deviceId)
            ->where('ip_address', $deviceIp)->first();

        if($user && Hash::check($password, $user->secret)){
            $secretPlain = Str::random(64);
            $secret = Hash::make($secretPlain);

            $user->secret = $secret;
            $user->save();

            $token = $user->createToken('rfid_terminal_access_client')->accessToken;

            return $this->respondWithTokenAndMeta($token, $user, [
                'attendance_endpoint' => url('/api/v1/terminal/attendance'),
                'secret' => $secretPlain
            ]);
        }

        return $this->respondWithError('INVALID_CREDENTIALS', 401, 'Invalid credentials');
    }
}
