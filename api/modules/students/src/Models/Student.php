<?php

namespace Tamani\Students\Models;

use App\Support\UUIDModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tamani\RfidTerminal\Traits\HasAttendance;
use Tamani\RfidTerminal\Traits\HasTagAllocation;
use Tamani\Students\Factories\StudentFactory;

class Student extends Model
{
    use HasFactory;
    use UUIDModel;
    use Notifiable;
    use HasTagAllocation;
//    use HasAttendance;

    const FILLABLE = [
        'student_id',
        'first_name',
        'last_name',
        'contact_person',
        'contact_number',
        'contact_address',
    ];
    /**
     * Student constructor.
     */
    public function __construct(array $attributes = [])
    {
        $this->fillable = self::FILLABLE;
        parent::__construct($attributes);
    }

    protected static function newFactory(): StudentFactory
    {
        return new StudentFactory();
    }
}
