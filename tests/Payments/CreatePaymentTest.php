<?php

namespace Tests\Invoices;


use Carbon\Carbon;
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
                'invoice' => [
                    'accounting_id' => ''
                ],
                'account' => [
                    'accounting_id' => ''
                ],
                'date' => Carbon::now()
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