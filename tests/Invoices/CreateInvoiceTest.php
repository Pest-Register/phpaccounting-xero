<?php

namespace Tests\Invoices;


use Tests\BaseTest;

class CreateInvoiceTest extends BaseTest
{
    public function testCreateInvoice(){
        $this->setUp();
        try {

            $params = [
                'type' => 'ACCREC',
                'date' => '2019-01-27 00:00:00',
                'due_date' => '2019-01-28 00:00:00',
                'contact' => '58697449-85ef-46ae-83fc-6a9446f037fb',
                'invoice_reference' => 'ORC1040',
                'invoice_data' => [
                    [
                        'description' => 'Consulting services as agreed (20% off standard rate)',
                        'quantity' => '10',
                        'unit_amount' => '100.00',
                        'discount_rate' => '20',
                        'amount' => 800,
                        'code' => 200
                    ]
                ]
            ];

            $response = $this->gateway->createInvoice($params)->send();
            if ($response->isSuccessful()) {
                $this->assertIsArray($response->getData());
                var_dump($response->getInvoices());
            }
            var_dump($response->getErrorMessage());
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}