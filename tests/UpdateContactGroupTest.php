<?php

namespace Tests;
use Faker;

class UpdateContactGroupTest extends BaseTest
{
    public function testUpdateContactGroups()
    {
        $this->setUp();
        $faker = Faker\Factory::create();
        try {

            $params = [
                'accounting_id' => '690d0004-ccd8-4267-aaf0-e65e40ec1bc7',
//                'name' => $faker->name,
//                'status' => 'ACTIVE',
                'contacts' => [
                    [
                        'accounting_id' => '540fcb05-f136-4658-a5b9-81265f8ad459'
                    ],
                    [
                        'accounting_id' => 'c1a96084-abba-4b9a-846c-acaa64e3d95b'
                    ]
                ]
            ];

            $response = $this->gateway->updateContactGroup($params)->send();
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