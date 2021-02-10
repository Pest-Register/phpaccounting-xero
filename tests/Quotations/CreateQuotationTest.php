<?php


namespace Tests\Quotations;


use Carbon\Carbon;
use Tests\BaseTest;

class CreateQuotationTest extends BaseTest
{
    public function testCreateQuotation(){
        $this->setUp();
        try {

            $params = [
                'date' => Carbon::now(),
                'expiry_date' => Carbon::now(),
                'contact' => '58697449-85ef-46ae-83fc-6a9446f037fb',
                'quotation_reference' => 'YEET',
                'quotation_number' => 'adsadasdasd',
                'terms' => 'Please accept quote by expiry date',
                'quotation_data' => [
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

            $response = $this->gateway->createQuotation($params)->send();
            if ($response->isSuccessful()) {
                $this->assertIsArray($response->getData());
                var_dump($response->getQuotations());
            }
            var_dump($response->getErrorMessage());
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}