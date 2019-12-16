<?php
/**
 * Created by IntelliJ IDEA.
 * User: Max
 * Date: 5/29/2019
 * Time: 5:42 PM
 */

namespace Tests\Invoices;


use Tests\BaseTest;

class GetInvoiceTest extends BaseTest
{

    public function testGetInvoices()
    {
        $this->setUp();
        try {
            $params = [
                'accounting_ids' => ["f571c38b-5be1-41e1-ad5a-ff6184284beb"],
                'page' => 1
            ];

            $response = $this->gateway->getInvoice($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getInvoices());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}