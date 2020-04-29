<?php

namespace PHPAccounting\Xero\Message\InventoryItems\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

/**
 * Update Inventory Item(s) Response
 * @package PHPAccounting\XERO\Message\InventoryItems\Responses
 */
class UpdateInventoryItemResponse extends AbstractResponse
{

    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if ($this->data) {
            if(array_key_exists('status', $this->data)){
                return !$this->data['status'] == 'error';
            }
            if ($this->data instanceof \XeroPHP\Remote\Collection) {
                if (count($this->data) == 0) {
                    return false;
                }
            } elseif (is_array($this->data)) {
                if (count($this->data) == 0) {
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return string
     */
    public function getErrorMessage(){
        if ($this->data) {
            if(array_key_exists('status', $this->data)){
                return ErrorResponseHelper::parseErrorResponse($this->data['detail'], 'Inventory Item');
            }
            if (count($this->data) === 0) {
                return 'NULL Returned from API or End of Pagination';
            }
        }
        return null;
    }

    public function parsePurchaseDetails($data, $item, $isTracked) {
        if ($data) {
            if ($isTracked) {
                $item['buying_account_code'] = IndexSanityCheckHelper::indexSanityCheck('COGSAccountCode', $data);
            } else {
                $item['buying_account_code'] = IndexSanityCheckHelper::indexSanityCheck('AccountCode', $data);
            }

            $item['buying_tax_type_code'] = IndexSanityCheckHelper::indexSanityCheck('TaxType', $data);
            $item['buying_unit_price'] = IndexSanityCheckHelper::indexSanityCheck('UnitPrice', $data);
        }

        return $item;
    }

    public function parseSalesDetails($data, $item) {
        if ($data) {
            $item['selling_account_code'] = IndexSanityCheckHelper::indexSanityCheck('AccountCode', $data);
            $item['selling_tax_type_code'] = IndexSanityCheckHelper::indexSanityCheck('TaxType', $data);
            $item['selling_unit_price'] = IndexSanityCheckHelper::indexSanityCheck('UnitPrice', $data);
        }

        return $item;
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
            $newItem['type'] = 'UNSPECIFIED';
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