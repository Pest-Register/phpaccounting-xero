<?php
/**
 * Created by IntelliJ IDEA.
 * User: Max
 * Date: 5/29/2019
 * Time: 12:32 PM
 */

namespace Tests;
use Faker;

class DeleteContactGroupTest extends BaseTest
{
    /**
     *
     */
    public function testDeleteContactGroupAndAllContacts()
    {
        $this->setUp();
        $faker = Faker\Factory::create();
        try {

            $params = [
                'accounting_id' => '488d74bf-65a9-448c-ac06-cdb9288761ca',
                'status' => 'DELETED'
            ];

            $response = $this->gateway->deleteContactGroup($params)->send();
            if ($response->isSuccessful()) {
                $contactGroups = $response->getContactGroups();
                var_dump($contactGroups);
                $this->assertIsArray($contactGroups);
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }

    /**
     *
     */
    public function testDeleteContactGroupContacts() {
        {
            $this->setUp();
            $faker = Faker\Factory::create();
            try {

                $params = [
                    'accounting_id' => 'a04d495e-bd39-4fb1-a0af-a9b3cdf82c86',
                    'status' => 'ACTIVE',
                    'contacts' => [
                        [
                            'accounting_id' => 'f20f1d23-a10f-4746-b7df-b48d87a23182'
                        ]
                    ]
                ];

                $response = $this->gateway->deleteContactGroup($params)->send();
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
}