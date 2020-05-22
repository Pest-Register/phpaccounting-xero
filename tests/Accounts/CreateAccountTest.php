<?php

namespace Tests;
use Faker;
class CreateAccountTest extends BaseTest
{
    public function testCreateAccount(){
        $this->setUp();
        try {

            $params = [
                'code' => '007',
                'name' => 'PESTREGISTER_General Asset Account',
                'type' => 'EXPENSE',
                'status' => 'ACTIVE',
                'description' => 'Test Description',
                'tax_type' => 'INPUT',
                'enable_payments_to_account' => true,
                'show_inexpense_claims' => true
            ];

            $response = $this->gateway->createAccount($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getAccounts());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}