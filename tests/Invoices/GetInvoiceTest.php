<?php
/**
 * Created by IntelliJ IDEA.
 * User: Max
 * Date: 5/29/2019
 * Time: 5:42 PM
 */

namespace Tests\Invoices;


use Illuminate\Support\Facades\Log;
use Tests\BaseTest;

class GetInvoiceTest extends BaseTest
{

    public function testGetInvoices()
    {
        $this->setUp();
        try {
            $params = [
                'accounting_ids' => ["e017e7fa-31cc-442f-8a9e-c16a70a9e15e"],
                'page' => 1
            ];

            $response = $this->gateway->getInvoice($params)->send();
            if ($response->isSuccessful()) {
                echo print_r($response->getInvoices(), true);
            } else {
                 var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}
