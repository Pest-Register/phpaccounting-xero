<?php
namespace Tests;
use Faker;
use Tests\BaseTest;

class DeleteAccountTest extends BaseTest
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

            $response = $this->gateway->deleteAccount($params)->send();
            if ($response->isSuccessful()) {
                $accounts = $response->getAccounts();
                var_dump($accounts);
                $this->assertIsArray($accounts);
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}