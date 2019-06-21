<?php

namespace Tests;
use Faker;
class CreateAccountTest extends BaseTest
{
    public function testCreateContacts()
    {
        $this->setUp();
        $faker = Faker\Factory::create();
        try {

            $params = [];

            $response = $this->gateway->createAccount($params)->send();
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