<?php

namespace App\Service;

use RuntimeException;

class ExchangeRateApiProvider implements RateProviderInterface
{

    private const string API_URL = 'https://api.apilayer.com/exchangerates_data/latest';


    public function getRate(string $currency): float
    {
        $accessKey = $_ENV['RATES_API_KEY'] ?? null;
        if (!$accessKey) {
            throw new RuntimeException('Rates API key missing');
        }

        $url = self::API_URL . '?symbols=' . urlencode($currency) . '&base=EUR';

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => [
                'Content-Type: text/plain',
                'apikey: ' . $accessKey,
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        if (!$response) {
            throw new RuntimeException('Rates service unavailable');
        }

        $data = json_decode($response, true);

        if (isset($data['message']) && $data['message'] === 'Invalid authentication credentials') {
            throw new RuntimeException('Rates API authentication error: Invalid credentials');
        }

        if (isset($data['error'])) {
            throw new RuntimeException('Rates API error: ' . json_encode($data['error']));
        }

        if (!isset($data['rates'][$currency])) {
            throw new RuntimeException("Currency rate not found for: $currency");
        }

        return $data['rates'][$currency];
    }
}
