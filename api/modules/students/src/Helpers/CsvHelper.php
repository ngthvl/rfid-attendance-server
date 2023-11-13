<?php


namespace Tamani\Students\Helpers;


use Tamani\Students\Enums\Mapping;

class CsvHelper
{
    public static function mapStudentDataCsv(array $line){
        $constructed = [];

        foreach ($line as $idx => $value){
            $constructed[Mapping::STUDENT_CSV_MAP[$idx]] = $value;
        }
        return $constructed;
    }
}
