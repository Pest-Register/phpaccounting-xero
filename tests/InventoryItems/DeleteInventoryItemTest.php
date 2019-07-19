<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 19/07/2019
 * Time: 2:48 PM
 */

namespace Tests\InventoryItems;


use Tests\BaseTest;

class DeleteInventoryItemTest extends BaseTest
{

    public function testGetInventoryItems()
    {
        $this->setUp();
        try {
            $params = [
                'accounting_id' => "6d89b734-3639-4571-9ed0-eb0a25f82b4b"
            ];

            $response = $this->gateway->deleteInventoryItem($params)->send();
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