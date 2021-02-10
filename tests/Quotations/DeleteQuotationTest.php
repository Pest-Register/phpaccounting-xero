<?php


namespace Tests\Quotations;


use Tests\BaseTest;

class DeleteQuotationTest extends BaseTest
{
    /**
     *
     */
    public function testDeleteQuotation()
    {
        $this->setUp();
        try {

            $params = [
                'accounting_id' => '0b2d88f5-d352-4ef2-a6f1-10cfec70fb85',
                'status' => 'DELETED'
            ];

            $response = $this->gateway->deleteQuotation($params)->send();
            if ($response->isSuccessful()) {
                $quotes = $response->getQuotations();
                var_dump($quotes);
                $this->assertIsArray($quotes);
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}