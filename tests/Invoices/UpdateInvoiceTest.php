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
                'accounting_id' => '0e64a623-c2a1-446a-93ed-eb897f118cbc',
                'type' => 'ACCREC',
                'date' => '2019-11-13 00:00:00',
                'contact' => '860b99a9-0958-4c8d-a98f-bb1f092b16bb',
                'email_status' => true,
                'amount_paid' => 0.0,
                'amount_due' => 825.0,
                'invoice_data' => [
                    [
                        'description' => 'Feedback sessions with your stakeholders',
                        'accounting_id' => 'ad4dfaa6-9705-4c58-8c39-3ee3fc3c6bbf',
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
                'invoice_reference' => 'ORC1027',
                'total' => '825.00',
                'gst_inclusive' => 'EXCLUSIVE',
                'sync_token' => NULL,
                'status' => 'SUBMITTED',
            ];

            $response = $this->gateway->updateInvoice($params)->send();
            if ($response->isSuccessful()) {
                $invoices = $response->getInvoices();
                $this->assertIsArray($invoices);
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}