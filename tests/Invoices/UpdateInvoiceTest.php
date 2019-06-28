<?php
/**
 * Created by IntelliJ IDEA.
 * User: Max
 * Date: 5/29/2019
 * Time: 6:21 PM
 */

namespace Tests\Invoices;


use Tests\BaseTest;
use Faker;
class UpdateInvoiceTest extends BaseTest
{
    public function testUpdateInvoices()
    {
        $this->setUp();
        $faker = Faker\Factory::create();
        try {

            $params = [
                'accounting_id' => 'fc714cd8-98b6-44b9-90a4-55189d56872d',
                'type' => 'ACCREC',
                'contact' => 'd6a384fb-f46f-41a3-8ac7-b7bc9e0b5efa',
                'invoice_data' => [
                    [
                        'description' => $faker->sentence,
                        'quantity' => '20',
                        'unit_amount' => '100.00',
                        'discount_rate' => '10',
                        'code' => 200
                    ]
                ]
            ];

            $response = $this->gateway->updateInvoice($params)->send();
            if ($response->isSuccessful()) {
                $invoices = $response->getInvoice();
                $this->assertIsArray($invoices);
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}