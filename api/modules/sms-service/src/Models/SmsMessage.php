<?php

namespace Tamani\Sms\Models;

use App\Support\UUIDModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class SmsMessage extends Model
{
    use HasFactory, UUIDModel;

    protected static function boot (): void
    {
        // Boot other traits on the Model
        parent::boot();

        /**
         * Listen for the creating event on the user model.
         * Sets the 'id' to a UUID using Str::uuid() on the instance being created
         */
        static::creating(function ($model) {
            if ($model->getKey() === null) {
                $model->setAttribute($model->getKeyName(), Str::uuid()->toString());
            }

            $now = Carbon::now();

            $pb = PhoneBook::where('contact_number', $model->recipient)->first();

            if(!$pb){
                $pb = new PhoneBook();
                $pb->contact_number = $model->recipient;
                $pb->last_message = $now;
                $pb->save();
            }else{
                $pb->last_message = $now;
                $pb->save();
            }

            $model->phonebook_id = $pb->id;
        });
    }
}
