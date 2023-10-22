<?php

namespace Tamani\RfidTerminal\Tests\Unit;

use Tamani\RfidTerminal\Models\RfidTerminal;
use Tests\TestCase;

class RfidTerminalUnitTest extends TestCase
{
    public function test_that_rfid_terminal_model_can_store_values()
    {
        $deviceInfo = [
            "device_name" => "Lavina",
            "ip_address" => "6.102.202.168"
        ];

        RfidTerminal::factory()->create($deviceInfo);

        $this->assertDatabaseHas('rfid_terminals', $deviceInfo);
    }
}
