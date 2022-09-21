<?php

namespace PHPAccounting\Xero\Message\InventoryItems\Responses;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;

/**
 * Create Inventory Item(s) Response
 * @package PHPAccounting\XERO\Message\InventoryItems\Responses
 */
class CreateInventoryItemResponse extends AbstractXeroResponse
{

    public function parsePurchaseDetails($data, $item, $isTracked) {
        if ($data) {
            if ($isTracked) {
                $item['buying_account_code'] = IndexSanityCheckHelper::indexSanityCheck('COGSAccountCode', $data);
            } else {
                $item['buying_account_code'] = IndexSanityCheckHelper::indexSanityCheck('AccountCode', $data);
            }

            $item['buying_tax_type_id'] = IndexSanityCheckHelper::indexSanityCheck('TaxType', $data);
            $item['buying_unit_price'] = IndexSanityCheckHelper::indexSanityCheck('UnitPrice', $data);
        }

        return $item;
    }

    public function parseSalesDetails($data, $item) {
        if ($data) {
            $item['selling_account_code'] = IndexSanityCheckHelper::indexSanityCheck('AccountCode', $data);
            $item['selling_tax_type_id'] = IndexSanityCheckHelper::indexSanityCheck('TaxType', $data);
            $item['selling_unit_price'] = IndexSanityCheckHelper::indexSanityCheck('UnitPrice', $data);
        }

        return $item;
    }

    private function parseType($data) {
        return $data;
    }

    /**
     * Return all Payments with Generic Schema Variable Assignment
     * @return array
     */
    public function getInventoryItems(){
        $items = [];
        foreach ($this->data as $item) {
            $newItem = [];
            $newItem['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('ItemID', $item);
            $newItem['code'] = IndexSanityCheckHelper::indexSanityCheck('Code', $item);
            $newItem['name'] = IndexSanityCheckHelper::indexSanityCheck('Name', $item);
            $newItem['description'] = IndexSanityCheckHelper::indexSanityCheck('Description', $item);
            $newItem['type'] = $this->parseType(null);
            $newItem['is_buying'] = IndexSanityCheckHelper::indexSanityCheck('IsPurchased', $item);
            $newItem['is_selling'] = IndexSanityCheckHelper::indexSanityCheck('IsSold', $item);
            $newItem['is_tracked'] = IndexSanityCheckHelper::indexSanityCheck('IsTracked', $item);
            $newItem['buying_description'] = IndexSanityCheckHelper::indexSanityCheck('PurchaseDescription', $item);
            $newItem['selling_description'] = IndexSanityCheckHelper::indexSanityCheck('Description', $item);
            $newItem['quantity'] = IndexSanityCheckHelper::indexSanityCheck('QuantityOnHand', $item);
            $newItem['cost_pool'] = IndexSanityCheckHelper::indexSanityCheck('TotalCostPool', $item);
            $newItem['updated_at'] = IndexSanityCheckHelper::indexSanityCheck('UpdatedDateUTC', $item);

            if (IndexSanityCheckHelper::indexSanityCheck('PurchaseDetails', $item)) {
                if (IndexSanityCheckHelper::indexSanityCheck('IsTrackedAsInventory', $item)) {
                    $newItem = $this->parsePurchaseDetails($item['PurchaseDetails'], $newItem, $item['IsTrackedAsInventory']);
                } else {
                    $newItem = $this->parsePurchaseDetails($item['PurchaseDetails'], $newItem, false);
                }
            }
            if (IndexSanityCheckHelper::indexSanityCheck('SalesDetails', $item)) {
                $newItem = $this->parseSalesDetails($item['SalesDetails'], $newItem);
            }

            array_push($items, $newItem);
        }

        return $items;
    }
}