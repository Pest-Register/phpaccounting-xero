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
                'currency' => 'AUD',
                'reference_id' => 'Test',
                'is_reconciled' => false,
                'date' => '2019-27-06 00:00:00',
                'contact' => [
                    'accounting_id' => '9686b659-42f3-4993-8d09-1977f429b3cc'
                ],
                'invoice' => [
                    'accounting_id' => 'dfa9ab00-a59f-4f51-9f4f-408c63622331'
                ],
                'account' => [
                    'accounting_id' => '719bcd89-7863-4e2f-aea1-925025e039fe'
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