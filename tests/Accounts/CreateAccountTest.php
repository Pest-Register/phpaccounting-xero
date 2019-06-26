<?php

namespace Tests;
use Faker;
class CreateAccountTest extends BaseTest
{
    public function testCreateAccount(){
        $this->setUp();
        try {

            $params = [
                'code' => 999,
                'name' => 'Test',
                'type' => 'EXPENSE',
                'status' => 'ACTIVE',
                'description' => 'Test Description',
                'tax_type' => 'INPUT',
                'enable_payments_to_account' => true,
                'show_inexpense_claims' => true
            ];

            $response = $this->gateway->createAccount($params)->send();
            if ($response->isSuccessful()) {
                $this->assertIsArray($response->getData());
                var_dump($response->getAccounts());
            }
            var_dump($response->getErrorMessage());
        } catch (\Exception $exception) {
            var_dump($exception->getTrace());
        }
    }
}