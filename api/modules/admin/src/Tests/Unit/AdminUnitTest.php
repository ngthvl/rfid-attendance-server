<?php

namespace Tamani\Admin\Tests\Unit;

use Faker\Provider\Internet;
use Faker\Provider\Person;
use Illuminate\Support\Facades\Hash;
use Tamani\Admin\Models\Admin;
use Tests\TestCase;

class AdminUnitTest extends TestCase
{
    public function test_that_admin_model_can_store_values()
    {
        $userInfo = [
            'name' => Person::firstNameMale(),
            'email' => Internet::safeEmailDomain()
        ];

        $admin = new Admin($userInfo);

        $password = 'password';

        $admin->password = Hash::make($password);

        $admin->save();

        $this->assertDatabaseHas('admins', $userInfo);
    }
}
