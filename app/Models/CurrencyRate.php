<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class CurrencyRate extends Model
{
    protected $table = 'currency_rates';

    protected $fillable = [
        'from',
        'to',
        'rate',
        'user_id',
        'deleted_at'
    ];

    public static function myList()
    {
        $rates = CurrencyRate::whereNull('deleted_at')->get();
        $response = [];
        foreach ($rates as $index => $rate) {
            $rate['code'] = strval($rate['code']);
//            if (!$rate['code']) continue;

            $rate['from'] = Currency::find($rate['from']);
            $rate['to'] = Currency::find($rate['to']);

            $response[] = $rate;
        }
        return $response;
    }

    public static function storeRate($data)
    {
        if (in_array(false, $data)) return 'false';

        $user = Auth::user();

        if (!$user) return false;

        $user_id = $user->id;

        if (!$user_id) return false;

        $data['user_id'] = $user_id;

        $data['from'] = Currency::where('code', $data['from'])->pluck('id')->first();
        $data['to'] = Currency::where('code', $data['to'])->pluck('id')->first();

        if (CurrencyRate::create($data)) {
            return 'true';
        }
    }

    public static function isExist($from = false, $to = false)
    {
        if (false == $from || false == $to) return 'false';

        $user = Auth::user();

        if (!$user) return 'false';

        $user_id = $user->id;

        if (!$user_id) return 'false';

        $from = Currency::where('code', $from)->pluck('id')->first();
        $to = Currency::where('code', $to)->pluck('id')->first();

        $rate = CurrencyRate::where('from', $from)->where('to', $to)->where('user_id', $user_id)->get();

        if (!$rate) return 'false';

        return (bool)$rate->count();

    }

}