<?php
namespace Tamani\Admin\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as BaseUser;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Tamani\Admin\Factories\AdminFactory;

class Admin extends BaseUser implements Authenticatable, HasMedia
{
    use HasApiTokens, HasFactory, InteractsWithMedia;

    const FILLABLE = [
        'name', 'email'
    ];

    public function __construct(array $attributes = [])
    {
        $this->fillable = self::FILLABLE;
        parent::__construct($attributes);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): AdminFactory
    {
        return new AdminFactory();
    }
}
