<?php

namespace App\Services;

use App\Repositories\Crypto\CoinMarketCapApiRepository;

class CryptoService
{
    public function getTopCryptos(): array {
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/listings/latest';
        $parameters = [
            'start' => '1',
            'limit' => '75',
            'convert' => 'USD'
        ];

        return (new CoinMarketCapApiRepository())->getCrypto($url, $parameters)['data'];
    }

    public function getCryptoById($cryptoId): array {
        $url = 'https://pro-api.coinmarketcap.com/v2/cryptocurrency/quotes/latest';
        $parameters = [
            'convert' => 'USD',
            'id' => $cryptoId
        ];

        return (new CoinMarketCapApiRepository())->getCrypto($url, $parameters)['data'][$cryptoId];
    }

    public function getCryptoBySlug($cryptoSlug): int {
        $url = 'https://pro-api.coinmarketcap.com/v2/cryptocurrency/quotes/latest';
        $parameters = [
            'convert' => 'USD',
            'slug' => strtolower($cryptoSlug)
        ];

        if(!isset((new CoinMarketCapApiRepository)->getCrypto($url, $parameters)['data'])) {
            return false;
        }

        $id = array_keys((new CoinMarketCapApiRepository)->getCrypto($url, $parameters)['data']);

        return $id[0];
    }

    public function getCryptoBySymbol($cryptoSymbol): int {
        $cryptoSymbol = strtoupper($cryptoSymbol);
        $url = 'https://pro-api.coinmarketcap.com/v2/cryptocurrency/quotes/latest';
        $parameters = [
            'convert' => 'USD',
            'symbol' => $cryptoSymbol
        ];

        $crypto = (new CoinMarketCapApiRepository())->getCrypto($url, $parameters)['data'][$cryptoSymbol][0];

        if(!isset($crypto[0])) {
            return false;
        }
        return $crypto[0]['id'];
    }
}
