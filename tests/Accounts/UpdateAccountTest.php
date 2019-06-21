<?php

namespace Tests;

use Faker;
use Omnipay\Omnipay;

class UpdateAccountTest extends BaseTest
{
    public function testUpdateContacts()
    {
        $this->setUp();
        $faker = Faker\Factory::create();
        try {

            $params = [
                'accounting_id' => '565acaa9-e7f3-4fbf-80c3-16b081ddae10',
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