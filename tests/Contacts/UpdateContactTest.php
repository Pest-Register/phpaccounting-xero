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
                'accounting_id' => 'c40f872d-9b22-40e0-b2dc-9e1e7a6cbb01',
                'name' => $faker->name,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email_address' => $faker->email,
                'phones' => [
                    [
                        'type' => 'MOBILE',
                        'area_code' => '',
                        'country_code' => '61',
                        'phone_number' => '545346432'
                    ]
                ],
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