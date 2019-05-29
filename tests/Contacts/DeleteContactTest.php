<?php
/**
 * Created by IntelliJ IDEA.
 * User: Max
 * Date: 5/29/2019
 * Time: 2:38 PM
 */

namespace Tests\Contacts;
use Faker;
use Tests\BaseTest;

class DeleteContactTest extends BaseTest
{
    /**
     *
     */
    public function testDeleteContact()
    {
        $this->setUp();
        try {

            $params = [
                'accounting_id' => 'c40f872d-9b22-40e0-b2dc-9e1e7a6cbb01',
            ];

            $response = $this->gateway->deleteContact($params)->send();
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