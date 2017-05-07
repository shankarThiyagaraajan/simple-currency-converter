<?php

namespace App\Http\Controllers\Currency;

use App\Models\Currency;
use App\Models\CurrencyRate;
use App\Providers\AuthServiceProvider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

/**
 * Class CurrencyConverterController
 * @package App\Http\Controllers\Currency
 */
class CurrencyConverterController extends Controller
{
    /**
     * CurrencyConverterController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * To Landing Create Page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $currency = Currency::whereNull('deleted_at')->get();
        $rates = CurrencyRate::myList();

        return view('currency.manage', compact('currency', 'rates'));
    }

    /**
     * To Create New Currency.
     *
     * @param Request $request
     * @return bool|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $name = $request->get('name', false);
        $code = $request->get('code', false);

        if (false == $name || false == $code) return false;

        $data = [
            'currency' => $name,
            'code' => $code
        ];

        Currency::create($data);
        return redirect('/currency/add');
    }

    /**
     * To check as currency is exist or not.
     *
     * @param $code
     * @return bool|string
     */
    public function checkCurrency($code)
    {
        $code = strval($code);
        if (!$code) return false;

        return Currency::isExists($code);
    }

    /**
     * To landing page for create new rate.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rateCreate()
    {
        $currency = Currency::whereNull('deleted_at')->get();
        return view('home', compact('currency'));
    }

    /**
     * To create new rate.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|string
     */
    public function rateStore(Request $request)
    {
        $data['from'] = $request->get('currencyFrom', false);
        $data['to'] = $request->get('currencyTo', false);
        $data['rate'] = $request->get('rate', false);

        $status = 'true';

        if (false == $data['from'] || false == $data['to'] || false == $data['rate']) $status = 'false';

        $user = Auth::user();

        if (!$user) return 'false';

        $user_id = $user->id;

        if (!$user_id) return 'false';

        $data['user_id'] = $user_id;

        if (!CurrencyRate::isExist($data['from'], $data['to'])) {
            CurrencyRate::storeRate($data);
        }

        return redirect('/currency/add');
    }

    /**
     * To check, rate is exist or not.
     *
     * @param Request $request
     * @return bool|string
     */
    public function rateCheck(Request $request)
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);

        if (false == $from || false == $to) return 'false';

        return CurrencyRate::isExist($from, $to);
    }

    /**
     * To get rate.
     *
     * @param Request $request
     * @return bool|string
     */
    public function getRate(Request $request)
    {
        $from = $request->get('from', false);
        $to = $request->get('to', false);

        if (false == $from || false == $to) return 'false';

        $user = Auth::user();

        if (!$user) return false;

        $user_id = $user->id;

        if (!$user_id) return false;

        $from = Currency::where('code', $from)->pluck('id')->first();
        $to = Currency::where('code', $to)->pluck('id')->first();

        $rate = CurrencyRate::where('from', $from)->where('to', $to)->get();

        if (!$rate) return 'false';
        $rate = $rate->first();

        if (!$rate) return $rate;

        return $rate;
    }
}
