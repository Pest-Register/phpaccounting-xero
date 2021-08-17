<?php

namespace Tests\TaxRates;


use Tests\BaseTest;

class GetTaxRateTest extends BaseTest
{

    public function testGetTaxRates()
    {
        $this->setUp();
        try {
            $params = [
//                'accounting_ids' => [""],
//                'search_params' => [
//                    'TaxType' => 'INPUT',
//                ],
//                'search_filters' => [
//                    'TaxType' => [
//                        'INPUT'
//                    ]
//                ],
//                'exact_search_value' => true,
                'page' => 1
            ];

            $response = $this->gateway->getTaxRate($params)->send();
            if ($response->isSuccessful()) {
                echo print_r($response->getTaxRates(), true);
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}