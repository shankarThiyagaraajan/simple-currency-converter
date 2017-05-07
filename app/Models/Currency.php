<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';

    protected $fillable = [
        'currency',
        'code'
    ];

    public static function isExists($code)
    {
        $code = strval($code);
        if (!$code) return false;

        $currency = Currency::where('code', $code);
        if (!$currency || is_null($currency)) return 'false';

        return strval((bool)$currency->count());

    }
}
