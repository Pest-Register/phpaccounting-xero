<?php

namespace Tests;
use Faker;
class CreateContactTest extends BaseTest
{
    public function testCreateContacts()
    {
        $this->setUp();
        $faker = Faker\Factory::create();
        try {

            $params = [
                'name' => $faker->name,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email_address' => $faker->email,
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

            $response = $this->gateway->createContact($params)->send();
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