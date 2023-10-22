<?php

namespace Tamani\RfidTerminal\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Tamani\RfidTerminal\Http\Requests\StoreAuthRequest;
use Tamani\RfidTerminal\Models\RfidTerminal;

class AuthController extends Controller
{
    public function store(StoreAuthRequest $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->only(['device-id']);

        $user = RfidTerminal::where('id', $credentials['device-id'])->first();

        if(!$user){
            return $this->respondWithError("Credentials does not match with our records", 422);
        }

        $token = $user->createToken('rfid_terminal_access_client')->accessToken;

        return $this->respondWithToken($token, $user);
    }
}
