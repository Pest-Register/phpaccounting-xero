<?php

namespace Tests;

use Faker;
use Omnipay\Omnipay;

class UpdateAccountTest extends BaseTest
{
    public function testUpdateAccount()
    {
        $this->setUp();
        $faker = Faker\Factory::create();
        try {

            $params = [
                'accounting_id' => 'b086f4fc-b699-463e-8bcc-b8d56a3b3a45',
                'name' => 'Test 1',
                'type' => 'EXPENSE',
                'description' => 'Test Description 1',
                'tax_type' => 'INPUT',
                'enable_payments_to_account' => true,
                'show_inexpense_claims' => true
            ];

            $response = $this->gateway->updateAccount($params)->send();
            if ($response->isSuccessful()) {
                $accounts = $response->getAccounts();
                var_dump($accounts);
                $this->assertIsArray($accounts);
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}