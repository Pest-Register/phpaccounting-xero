<?php
namespace Tests;
use Faker;
use Tests\BaseTest;

class DeleteAccountTest extends BaseTest
{
    /**
     *
     */
    public function testDeleteAccount()
    {
        $this->setUp();
        try {

            $params = [
                'accounting_id' => 'b086f4fc-b699-463e-8bcc-b8d56a3b3a45',
                'status' => 'ARCHIVED'
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