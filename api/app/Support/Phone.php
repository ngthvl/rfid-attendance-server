<?php


namespace App\Support;


use Illuminate\Support\Str;

class Phone
{
    static function cleanPhoneNumber(string $phoneNumber)
    {
        $phoneNumber = Str::replace('+', '', $phoneNumber);

        if(substr($phoneNumber, 0, 1) === '9'){
            $phoneNumber = '63' . $phoneNumber;
        }

        if(substr($phoneNumber, 0, 2) === '09'){
            $phoneNumber = substr($phoneNumber, 1);
            $phoneNumber = '63' . $phoneNumber;
        }

        return $phoneNumber;
    }
}
