<?php

namespace App;

use App\Models\Account;

class ExchangeRate
{
    /**
     * @var mixed
     */
    private string $access_key;
    private string $endpoint;

    private static array $currencies = [];

    public function __construct()
    {
        $this->endpoint = 'latest';
        $this->access_key = env('EXCHANGE_RATES_API_KEY');
    }

    public function exchangeRateFor($currencySymbol): float
    {
        $ch = curl_init('http://api.exchangeratesapi.io/v1/'.$this->endpoint.'?access_key='.$this->access_key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $json = curl_exec($ch);
        curl_close($ch);

        $exchangeRates = json_decode($json, true);

        return $exchangeRates['rates'][$currencySymbol];
    }

    public function getCurrencies(): array
    {
        if(empty(self::$currencies)) {
            $ch = curl_init('http://api.exchangeratesapi.io/v1/' . $this->endpoint . '?access_key=' . $this->access_key);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $json = curl_exec($ch);
            curl_close($ch);

            $exchangeRates = json_decode($json, true);
            unset($exchangeRates['rates']["BTC"]);

            foreach (array_keys($exchangeRates['rates']) as $exchangeRate) {
                self::$currencies[]=$exchangeRate;
            }
        }
        return self::$currencies;
    }
}
