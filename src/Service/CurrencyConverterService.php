<?php

namespace App\Service;

use JsonException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CurrencyConverterService
{
    private ParameterBagInterface $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /**
     * @throws JsonException
     */
    public function getCurrency(string $currency, string $currencyConverted): float
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->params['currency_convert_api'] . $currency,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ]);

        $response = json_decode(curl_exec($curl), JSON_THROW_ON_ERROR, 512, JSON_THROW_ON_ERROR);
        $rates = $response['rates'];
        $priceConverted = $rates[strtoupper($currencyConverted)];
        curl_close($curl);

        return $priceConverted;
    }
}
