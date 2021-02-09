<?php


namespace Tests\Quotations;


use Tests\BaseTest;

class GetQuotationTest extends BaseTest
{

    public function testGetQuotations()
    {
        $this->setUp();
        try {
            $params = [
                'accounting_ids' => [""],
                'page' => 1
            ];

            $response = $this->gateway->getQuotation($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getQuotations());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}