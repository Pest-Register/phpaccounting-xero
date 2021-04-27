<?php

namespace Tests\Invoices;


use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
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
                'contact' => '4bb77692-42d4-4565-85a0-8849eb85e039',
                'invoice_reference' => 'YEET',
                'invoice_number' => 'test missing data',
                'invoice_data' => [
                    [
                        'description' => 'Test',
                        'accounting_id' => '',
                        'quantity' => 1.0,
                        'unit_amount' => 220.0,
                        'discount_rate' => 0.0,
                        'code' => '245',
                        'tax_type' => 'OUTPUT',
                        'unit' => 'QTY',
                        'tax_id' => 'OUTPUT',
                        'account_id' => '245',
                        'item_code' => 'PR1'
                    ]
                ]
            ];

            $response = $this->gateway->createInvoice($params)->send();
            if ($response->isSuccessful()) {
                $this->assertIsArray($response->getData());
                echo print_r($response->getInvoices(), true);
            }
            var_dump($response->getErrorMessage());
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}