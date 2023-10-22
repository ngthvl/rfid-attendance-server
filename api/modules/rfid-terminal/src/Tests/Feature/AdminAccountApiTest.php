<?php

namespace Tamani\Admin\Tests\Feature;

use Tamani\Admin\Models\Admin;
use Tests\TestCase;

class AdminAccountApiTest extends TestCase
{
    public function test_that_admin_can_list_admin_accounts()
    {
        $user = Admin::factory(7)->create();
        $userCurrent = $user->first();

        $res = $this->actingAs($userCurrent)
            ->getJson('api/v1/admin');

        $res->assertJsonStructure([
            'data' => [
                [
                    'name', 'email'
                ]
            ]
        ])->assertSuccessful();

        $this->assertCount(7, $res['data']);
    }
}
