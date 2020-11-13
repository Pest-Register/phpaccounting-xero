<?php

namespace Tests\Invoices;


use Carbon\Carbon;
use Tests\BaseTest;

class CreateInvoiceTest extends BaseTest
{
    public function testCreateInvoice(){
        $this->setUp();
        try {

            $params = [
                'type' => 'ACCREC',
                'date' => Carbon::now(),
                'due_date' => Carbon::now(),
                'contact' => '58697449-85ef-46ae-83fc-6a9446f037fb',
                'invoice_reference' => 'YEET',
                'invoice_number' => 'adsadasdasd',
                'invoice_data' => [
                    [
                        'description' => 'Test',
                        'accounting_id' => '',
                        'quantity' => 1.0,
                        'unit_amount' => 220.0,
                        'discount_rate' => 0.0,
                        'code' => '200',
                        'tax_type' => 'OUTPUT',
                        'unit' => 'QTY',
                        'tax_id' => 'OUTPUT',
                        'account_id' => '200',
                    ],
                    [
                        'description' => 'Test',
                        'accounting_id' => '',
                        'quantity' => 1.0,
                        'unit_amount' => 110.0,
                        'discount_rate' => 100.0,
                        'code' => '200',
                        'tax_type' => 'OUTPUT',
                        'unit' => 'QTY',
                        'tax_id' => 'OUTPUT',
                        'account_id' => '200',
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