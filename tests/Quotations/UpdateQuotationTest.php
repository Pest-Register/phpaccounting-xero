<?php


namespace Tests\Quotations;


use Carbon\Carbon;
use Tests\BaseTest;

class UpdateQuotationTest extends BaseTest
{
    public function testUpdateQuotations()
    {
        $this->setUp();
        try {

            $params = [
                'accounting_id' => '7ee8c4ab-21d8-43f5-8366-5bdb6c46bf5b',
                'date' => Carbon::create('2021-02-10'),
                'expiry_date' => Carbon::create('2021-02-10 00:00:00'),
                'contact' => '58697449-85ef-46ae-83fc-6a9446f037fb',
                'quotation_data' => [
                    [
                        'description' => 'Development work - developer onsite per day',
                        'accounting_id' => 'c27221d7-8290-4204-9f3d-0cfb7c5a3d6f',
                        'amount' => '750.00',
                        'quantity' => 5,
                        'unit_amount' => '150.00',
                        'discount_rate' => 0.0,
                        'code' => '200',
                        'tax_type' => 'OUTPUT',
                        'unit' => 'QTY',
                        'tax_id' => 'OUTPUT',
                        'account_id' => '200',
                    ]
                ],
                'total_discount' => 0,
                'gst_registered' => true,
                'quotation_reference' => 'ADSA',
                'total' => '825.00',
                'gst_inclusive' => 'EXCLUSIVE',
//                'status' => 'DRAFT',
            ];

            $response = $this->gateway->updateQuotation($params)->send();
            if ($response->isSuccessful()) {
                $quotes = $response->getQuotations();
                var_dump($quotes);
                $this->assertIsArray($quotes);
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}