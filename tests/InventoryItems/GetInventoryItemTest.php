<?php


namespace Tests\InventoryItems;


use Tests\BaseTest;

class GetInventoryItemTest extends BaseTest
{

    public function testGetInventoryItems()
    {
        $this->setUp();
        try {
            $params = [
                'accounting_ids' => [""],
                'page' => 1
            ];

            $response = $this->gateway->getInventoryItem($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getInventoryItems());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}