<?php

namespace Tests;

use Faker;
use Omnipay\Omnipay;

class UpdateContactTest extends BaseTest
{
    public function testUpdateContacts()
    {
        $this->setUp();
        $faker = Faker\Factory::create();
        try {

            $params = [
                'accounting_id' => '565acaa9-e7f3-4fbf-80c3-16b081ddae10',
                'addresses' => [
                    [
                        'type' => 'STREET',
                        'address_line_1' => $faker->streetAddress,
                        'city' => $faker->city,
                        'postal_code' => $faker->postcode,
                        'country' => $faker->country
                    ]
                ]
            ];

            $response = $this->gateway->updateContact($params)->send();
            if ($response->isSuccessful()) {
                $contacts = $response->getContacts();
                var_dump($contacts);
                $this->assertIsArray($contacts);
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}