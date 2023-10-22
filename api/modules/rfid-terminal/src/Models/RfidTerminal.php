<?php

namespace Tamani\RfidTerminal\Models;
use App\Support\UUIDModel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as BaseUser;
use Laravel\Sanctum\HasApiTokens;
use Tamani\RfidTerminal\Factories\RfidTerminalFactory;

class RfidTerminal extends BaseUser implements Authenticatable
{
    use HasApiTokens, HasFactory, UUIDModel;

    protected $casts = [
        'devices_status' => 'array'
    ];

    protected $fillable = [
        'device_name',
        'ip_address',
        'devices_status',
    ];

    protected static function newFactory(): RfidTerminalFactory
    {
        return new RfidTerminalFactory();
    }
}
