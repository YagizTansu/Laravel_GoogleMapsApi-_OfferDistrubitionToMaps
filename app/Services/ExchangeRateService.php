<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\CurrencyExchangeRate;
use App\Models\Currency;
use Carbon\Carbon;

class ExchangeRateService{
    function updateTodayCurrencyExchangeRate()
    {
        $currencies = [
            'USD',
            'EUR'
        ];

        $response = Http::get('https://www.tcmb.gov.tr/kurlar/today.xml');
        $xml = simplexml_load_string($response->body());

        foreach ($xml as $row) {
            $currencyCode = (string)$row->attributes()['CurrencyCode'];
            if (in_array($currencyCode, $currencies)) {
                $currencyQuery = Currency::where('code', $currencyCode)->first();

                $currencyExchangeRate = CurrencyExchangeRate::where('currency_id', $currencyQuery->id)->where('date', Carbon::now()->format('Y-m-d'))->first();

                if (empty($currencyExchangeRate)) {
                    CurrencyExchangeRate::create([
                        'currency_id' => $currencyQuery->id,
                        /*'buying' => (float)$row->BanknoteBuying,
                        'selling' => (float)$row->BanknoteSelling,*/
                        'buyying' => (float)$row->ForexBuying,
                        'selling' => (float)$row->ForexSelling,
                        'date' => Carbon::now()->format('Y-m-d')
                    ]);
                } else {
                    CurrencyExchangeRate::where('id', $currencyExchangeRate->id)->update([
                        'buyying' => (float)$row->ForexBuying,
                        'selling' => (float)$row->ForexSelling,
                    ]);
                }
            }
        }

        //$currencyExchangeRate = CurrencyExchangeRate::where('currency_id', 113)->where('date', Carbon::now()->format('Y-m-d'))->first();

        if (empty($currencyExchangeRate)) {
            CurrencyExchangeRate::create([
                'currency_id' => 113,
                'buyying' => 1,
                'selling' => 1,
                'date' => Carbon::now()->format('Y-m-d')
            ]);
        }

        $currencyExchangeRate = CurrencyExchangeRate::with('currency')->where('date', Carbon::now()->format('Y-m-d'))->get();
        $load = ['CurrencyExchangeRate' => $currencyExchangeRate];
        return $load;
    }

     function getExchangeRate()
     {
        $currencyExchangeRate = CurrencyExchangeRate::with('currency')->where('date', Carbon::now()->format('Y-m-d'))->get();
        if(empty($currencyExchangeRate)){
            $load = $this->updateTodayCurrencyExchangeRate();
        }else{
            $load = ['CurrencyExchangeRate' => $currencyExchangeRate];
        }
        return $load;
    }

}
