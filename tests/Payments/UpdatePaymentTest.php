<?php

namespace Tests\Invoices;


use Tests\BaseTest;

class UpdatePaymentTest extends BaseTest
{
    public function testUpdatePayment(){
        $this->setUp();
        try {

            $params = [
                'accounting_id' => '39cf4670-281d-40c3-a90d-6c2a6b051ff3',
                'is_reconciled' => true,
                'invoice' => [
                    'accounting_id' => '20594b79-4e44-43f4-96fa-292836fd0657'
                ],
                'account' => [
                    'accounting_id' => '13918178-849a-4823-9a31-57b7eac713d7'
                ],
                'amount' => 590
            ];

            $response = $this->gateway->updatePayment($params)->send();
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