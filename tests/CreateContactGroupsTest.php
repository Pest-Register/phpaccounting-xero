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
                'accounting_id' => '3567ace4-1dc9-40b3-b364-9b55d5841b22',
//                'name' => 'Contractors',
//                'status' => 'ACTIVE',
                'contacts' => [
                    [
                        'accounting_id' => '39acded4-3c5d-48b1-8acf-4df92abb56a7'
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