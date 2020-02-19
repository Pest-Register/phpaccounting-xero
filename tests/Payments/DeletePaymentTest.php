<?php


namespace Tests\Payments;


use Tests\BaseTest;

class DeletePaymentTest extends BaseTest
{
    public function testDeletePayment()
    {
        $this->setUp();
        try {

            $params = [
                'accounting_id' => '22974891-3689-4694-9ee7-fd2ba917af55',
                'status' => 'DELETED'
            ];

            $response = $this->gateway->deletePayment($params)->send();
            if ($response->isSuccessful()) {
                $payments = $response->getPayments();
                $this->assertIsArray($payments);
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}