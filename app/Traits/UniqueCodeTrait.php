<?php

namespace App\Traits;

trait UniqueCodeTrait
{
    public static function createUniqueCode($column = 'unique_code', $length = 11, $prefix = null)
    {
        $table = (new static)->getTable();

        $uniqueCode = strtolower(\Str::random($length));
        if ($prefix) {
            $uniqueCode = $prefix . $uniqueCode;
        }

        $data = ['unique_code' => $uniqueCode];
        $rules = ['unique_code' => "unique:{$table},{$column}"];

        $validator = validator()->make($data, $rules);
        if($validator->fails()){
            static::createUniqueCode($length);
        }

        return $uniqueCode;
    }
}

