<?php

namespace Tamani\Admin\Http\Controllers\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Tamani\Admin\Http\Requests\StoreAuthRequest;
use Tamani\Admin\Http\Resources\AdminProfileResource;
use Tamani\Admin\Models\Admin;

class AuthController extends Controller
{
    public function store(StoreAuthRequest $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        $user = Admin::where('email', $credentials['email'])->first();

        if(!$user){
            return $this->respondWithError("Credentials does not match with our records", 422);
        }

        if(!Hash::check($credentials['password'], $user->password)){
            return $this->respondWithError("Credentials does not match with our records", 422);
        }

        $token = $user->createToken('personal')->accessToken;

        return $this->respondWithToken($token, $user);
    }

   public function me()
   {
       $me = auth()->user();

       return new AdminProfileResource($me);
   }
}
