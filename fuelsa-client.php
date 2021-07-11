<?php 

class FuelSAClient {
    private $apikey;

    function __construct($key) {
        $this->apikey = $key;
    }

    function getCurrentFuelPrices() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.fuelsa.co.za/exapi/fuel/current");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['key: ' . $this->apikey]);
        $body = curl_exec($ch); 
        return $body;
    }

    function getFuelPricesByYear($year) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.fuelsa.co.za/exapi/fuel/byyear/" . $year);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['key: ' . $this->apikey]);
        $body = curl_exec($ch); 
        return $body;
    }
}

?>