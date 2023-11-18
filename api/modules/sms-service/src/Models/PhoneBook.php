<?php

namespace Tamani\Sms\Models;

use App\Support\UUIDModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneBook extends Model
{
    use HasFactory, UUIDModel;
}
