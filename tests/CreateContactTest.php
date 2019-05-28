<?php

namespace Tests;
use Omnipay\Omnipay;
use PHPUnit\Framework\TestCase;
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
                ]
            ];

            $response = $this->gateway->createContact($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getContacts());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}