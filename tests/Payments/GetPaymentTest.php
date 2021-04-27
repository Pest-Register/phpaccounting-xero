<?php
/**
 * Created by IntelliJ IDEA.
 * User: Max
 * Date: 5/29/2019
 * Time: 5:42 PM
 */

namespace Tests\Invoices;

use Tests\BaseTest;

class GetPaymentTest extends BaseTest
{

    public function testGetPayments()
    {
        $this->setUp();
        try {
            $params = [
                'accounting_ids' => ["c3abac20-60d9-4c6f-8365-e276c9762e57"],
                'page' => 1
            ];

            $response = $this->gateway->getPayment($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getPayments());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}