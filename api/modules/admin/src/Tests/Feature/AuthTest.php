<?php

namespace Tamani\Admin\Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Tamani\Admin\Models\Admin;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_api_can_issue_token()
    {
        $password = "password";

        $user = Admin::factory()->create([
            'password' => Hash::make($password)
        ]);

        $this->post('api/v1/auth/login', ['email'=>$user->email, 'password'=>$password])
            ->assertSuccessful()
            ->assertJsonStructure([
                'data'=>[
                    'access_token',
                    'token_type',
                    'expires_in',
                    'user',
                ],
            ]);
    }

    public function test_api_can_catch_incorrect_credentials()
    {
        $password = "password";

        $user = Admin::factory()->create([
            'password' => Hash::make($password)
        ]);

        $this->actingAs($user)
            ->post('api/v1/auth/login', ['email'=>$user->email, 'password'=>"otherpass"])
            ->assertUnprocessable();
    }
}
