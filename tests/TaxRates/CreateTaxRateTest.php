<?php
namespace Tests\TaxRates;

use Tests\BaseTest;

class CreateTaxRateTest extends BaseTest
{

    public function testCreateTaxType(){
        $this->setUp();
        try {

            $params = [
                'name' => 'Development Operations Tax',
                'tax_type' => 'OUTPUT',
                'tax_components' => [
                    'name' => 'Development Tax',
                    'rate' => 10,
                    'is_compound' => false,
                    'is_non_recoverable' => false
                ],
                'status' => 'ACTIVE',
                'report_tax_type' => 'OUTPUT'
            ];

            $response = $this->gateway->createTaxRate($params)->send();
            if ($response->isSuccessful()) {
                $this->assertIsArray($response->getData());
                var_dump($response->getTaxRates());
            }
            var_dump($response->getErrorMessage());
        } catch (\Exception $exception) {
            var_dump($exception->getTrace());
        }
    }
}