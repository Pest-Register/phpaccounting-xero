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
                    'accounting_id' => 'dd3cae1f-6949-435a-802b-ab6d24f62f12',
                    'status' => 'ACTIVE',
                    'contacts' => [
                        [
                            'accounting_id' => '540fcb05-f136-4658-a5b9-81265f8ad459'
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