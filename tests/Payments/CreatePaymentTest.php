<?php

namespace Tests\Invoices;


use Tests\BaseTest;

class CreatePaymentTest extends BaseTest
{
    public function testCreatePayment(){
        $this->setUp();
        try {

            $params = [
                'currency_rate' => 1.0,
                'amount' => 100.00,
                'reference_id' => 'Test Description',
                'is_reconciled' => true,
                'date' => '2019-27-06',
                'invoice' => [
                    'accounting_id' => '30a87092-31b5-4a2c-831e-327486533dd2'
                ],
                'account' => [
                    'accounting_id' => '13918178-849a-4823-9a31-57b7eac713d7'
                ]
            ];

            $response = $this->gateway->createPayment($params)->send();
            if ($response->isSuccessful()) {
                $this->assertIsArray($response->getData());
                var_dump($response->getPayments());
            }
            var_dump($response->getErrorMessage());
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}