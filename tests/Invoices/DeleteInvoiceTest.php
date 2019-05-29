<?php
/**
 * Created by IntelliJ IDEA.
 * User: Max
 * Date: 5/29/2019
 * Time: 6:42 PM
 */

namespace Tests\Invoices;


use Tests\BaseTest;

class DeleteInvoiceTest extends BaseTest
{
    /**
     *
     */
    public function testDeleteInvoice()
    {
        $this->setUp();
        try {

            $params = [
                'accounting_id' => 'fc714cd8-98b6-44b9-90a4-55189d56872d',
                'status' => 'DELETED'
            ];

            $response = $this->gateway->deleteInvoice($params)->send();
            if ($response->isSuccessful()) {
                $invoices = $response->getInvoices();
                var_dump($invoices);
                $this->assertIsArray($invoices);
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}