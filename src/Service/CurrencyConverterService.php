<?php

namespace App\Service;

class CurrencyConverterService
{
    public function getCurrency(string $currency):void
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.exchangerate-api.com/v4/latest/$currency",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ]);
        $response = curl_exec($curl);
        var_dump($response);die();
        $err = curl_error($curl);

        curl_close($curl);
    }
}
