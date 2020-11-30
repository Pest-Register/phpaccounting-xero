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
                'accounting_ids' => [""],
                'search_param' => 'Name',
                'search_term' => 'Income',
                'page' => 1
            ];

            $response = $this->gateway->getTaxRate($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getTaxRates());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}