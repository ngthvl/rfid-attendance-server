<?php

namespace Tamani\RfidTerminal\Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Tamani\Admin\Models\Admin;
use Tamani\RfidTerminal\Models\RfidTerminal;
use Tests\TestCase;

class DeviceAuthTest extends TestCase
{
    public function test_api_can_issue_token()
    {
        $user = RfidTerminal::factory()->create();

        $response = $this->post('api/v1/auth/device', ['device-id'=>$user->id]);

        $response->assertSuccessful()
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
        RfidTerminal::factory()->create();

        $this->postJson('api/v1/auth/device', ['device-id'=>"invalid-id"])
            ->assertUnprocessable();
    }
}
