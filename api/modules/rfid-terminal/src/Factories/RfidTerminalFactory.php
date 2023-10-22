<?php

namespace Tamani\RfidTerminal\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tamani\RfidTerminal\Models\RfidTerminal;

class RfidTerminalFactory extends Factory
{
    protected $model = RfidTerminal::class;

    public function definition()
    {
        return [
            'device_name' => $this->faker->firstName(),
            'ip_address' => $this->faker->ipv4(),
            'devices_status' => ['COM1'=>'Online', 'COM2'=>'Online'],
        ];
    }
}
