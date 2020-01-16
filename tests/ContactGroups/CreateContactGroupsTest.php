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
                'name' => 'Contractors',
                'status' => 'ACTIVE',
                'contacts' => []
            ];

            $response = $this->gateway->createContactGroup($params)->send();
            if ($response->isSuccessful()) {
                $contactGroups = $response->getContactGroups();
                var_dump($contactGroups);
                $this->assertIsArray($contactGroups);
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}