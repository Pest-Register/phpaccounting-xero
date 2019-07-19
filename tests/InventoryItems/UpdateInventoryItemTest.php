<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 19/07/2019
 * Time: 2:48 PM
 */

namespace Tests\InventoryItems;


use Tests\BaseTest;

class UpdateInventoryItemTest extends BaseTest
{
    public function testGetInventoryItems()
    {
        $this->setUp();
        try {

            $params = [
                'accounting_id' => "ebdda49d-d57d-4ecb-934d-e8d3f8507f08",
                'code' => 'DEV-OPS',
                'name' => 'Development Operations Update',
                'is_selling' => true,
                'is_buying' => true,
                'description' => 'Development Operations',
                'buying_description' => 'Development Operations',
                'purchase_details' => [
                    'buying_unit_price' => 300,
                    'buying_account_code' => 200,
                    'buying_tax_type_code' => 'OUTPUT'
                ],
                'sales_details' => [
                    'selling_unit_price' => 150,
                    'selling_account_code' => 200,
                    'selling_tax_type_code' => 'OUTPUT'
                ]
            ];

            $response = $this->gateway->updateInventoryItem($params)->send();
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