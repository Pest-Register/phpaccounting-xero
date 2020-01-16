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
                'accounting_id' => '38b7b812-d334-46eb-872e-67c4cfb87538',
                'name' => 'Accrued Liabilities',
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
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}