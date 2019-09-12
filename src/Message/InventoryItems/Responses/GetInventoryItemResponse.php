<?php

namespace PHPAccounting\Xero\Message\InventoryItems\Responses;

use Omnipay\Common\Message\AbstractResponse;
use XeroPHP\Models\Accounting\Item;

/**
 * Get Inventory Item(s) Response
 * @package PHPAccounting\XERO\Message\Invoices\Responses
 */
class GetInventoryItemResponse extends AbstractResponse
{
    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if(array_key_exists('status', $this->data)){
            return !$this->data['status'] == 'error';
        }
        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return string
     */
    public function getErrorMessage(){
        if(array_key_exists('status', $this->data)){
            return $this->data['detail'];
        }
        return null;
    }

    public function parsePurchaseDetails($data, $item, $isTracked) {
        if ($data) {
            if ($isTracked) {
                $item['buying_account_code'] = $data->getCOGSAccountCode();
            } else {
                $item['buying_account_code'] = $data->getAccountCode();
            }

            $item['buying_tax_type_code'] = $data->getTaxType();
            $item['buying_unit_price'] = $data->getUnitPrice();
        }

        return $item;
    }

    public function parseSellingDetails($data, $item) {
        if ($data) {
            $item['selling_account_code'] = $data->getAccountCode();
            $item['selling_tax_type_code'] = $data->getTaxType();
            $item['selling_unit_price'] = $data->getUnitPrice();
        }

        return $item;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getInventoryItems(){
        $items = [];
        if ($this->data instanceof Item){
            $item = $this->data;
            $newItem = [];
            $newItem['accounting_id'] = $item->getItemID();
            $newItem['code'] = $item->getCode();
            $newItem['name'] = $item->getName();
            $newItem['description'] = $item->getDescription();
            $newItem['type'] = 'UNSPECIFIED';
            $newItem['is_buying'] = $item->getIsPurchased();
            $newItem['is_selling'] = $item->getIsSold();
            $newItem['is_tracked'] = $item->getIsTrackedAsInventory();
            $newItem['buying_description'] = $item->getPurchaseDescription();
            $newItem['selling_description'] = $item->getDescription();
            $newItem['asset_account_code'] = $item->getInventoryAssetAccountCode();
            $newItem['quantity'] = $item->getQuantityOnHand();
            $newItem['cost_pool'] = $item->getTotalCostPool();
            $newItem['updated_at'] = $item->getUpdatedDateUTC();
            $newItem = $this->parsePurchaseDetails($item->getPurchaseDetails(), $newItem, $item->getIsTrackedAsInventory());
            $newItem = $this->parseSellingDetails($item->getSalesDetails(), $newItem);
            array_push($items, $newItem);

        } else {
            foreach ($this->data as $item) {
                $newItem = [];
                $newItem['accounting_id'] = $item->getItemID();
                $newItem['code'] = $item->getCode();
                $newItem['name'] = $item->getName();
                $newItem['description'] = $item->getDescription();
                $newItem['type'] = 'UNSPECIFIED';
                $newItem['is_buying'] = $item->getIsPurchased();
                $newItem['is_selling'] = $item->getIsSold();
                $newItem['is_tracked'] = $item->getIsTrackedAsInventory();
                $newItem['buying_description'] = $item->getPurchaseDescription();
                $newItem['selling_description'] = $item->getDescription();
                $newItem['asset_account_code'] = $item->getInventoryAssetAccountCode();
                $newItem['quantity'] = $item->getQuantityOnHand();
                $newItem['cost_pool'] = $item->getTotalCostPool();
                $newItem['updated_at'] = $item->getUpdatedDateUTC();
                $newItem = $this->parsePurchaseDetails($item->getPurchaseDetails(), $newItem, $item->getIsTrackedAsInventory());
                $newItem = $this->parseSellingDetails($item->getSalesDetails(), $newItem);
                array_push($items, $newItem);
            }
        }


        return $items;
    }
}