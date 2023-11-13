<?php

namespace Tamani\RfidTerminal\Models;
use App\Support\UUIDModel;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as BaseUser;
use Laravel\Passport\HasApiTokens;
use Tamani\RfidTerminal\Factories\RfidTerminalFactory;

class RfidTerminal extends BaseUser implements Authenticatable
{
    use HasApiTokens, HasFactory, UUIDModel;

    protected $casts = [
        'devices_status' => 'array'
    ];

    const FILLABLE = [
        'id',
        'device_name',
        'ip_address',
        'devices_status',
    ];

    /**
     * RfidTerminal constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = self::FILLABLE;
        parent::__construct($attributes);
    }


    protected static function newFactory(): RfidTerminalFactory
    {
        return new RfidTerminalFactory();
    }
}
