<?php

namespace Tests;
use Faker;

class CreateContactGroupsTest extends BaseTest
{
    public function testCreateContactGroups()
    {
        $this->setUp();
        $faker = Faker\Factory::create();
        try {

            $params = [
                'name' => $faker->company,
                'status' => 'ACTIVE',
                'contacts' => [
                    [
                        'accounting_id' => '540fcb05-f136-4658-a5b9-81265f8ad459'
                    ]
                ]
            ];

            $response = $this->gateway->createContactGroup($params)->send();
            if ($response->isSuccessful()) {
                $contactGroups = $response->getContactGroups();
                var_dump($contactGroups);
                $this->assertIsArray($contactGroups);
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}