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
                ],
                'contact_groups' => [
                    [
                        'accounting_id' => '3567ace4-1dc9-40b3-b364-9b55d5841b22',
                        'name' => 'Contractors'
                    ]
                ]
            ];

            $response = $this->gateway->createContact($params)->send();
            if ($response->isSuccessful()) {
                $contacts = $response->getContacts();
                $this->assertIsArray($contacts);
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}