<?php

namespace PHPAccounting\Xero\Message\InventoryItems\Requests\Traits;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use XeroPHP\Models\Accounting\Item;

trait InventoryItemRequestTrait
{
    /**
     * Get Code Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getCode(){
        return $this->getParameter('code');
    }

    /**
     * Set Code Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param string $value Account Code
     */
    public function setCode($value){
        return $this->setParameter('code', $value);
    }

    /**
     * Get Inventory Asset AccountCode Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getInventoryAccountCode() {
        return $this->getParameter('inventory_account_code');
    }

    /**
     * Set Inventory Asset AccountCode Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setInventoryAccountCode($value) {
        return $this->setParameter('inventory_account_code', $value);
    }

    /**
     * Get Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getName() {
        return $this->getParameter('name');
    }

    /**
     * Set Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setName($value) {
        return $this->setParameter('name', $value);
    }

    /**
     * Get Is Buying Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getIsTracked() {
        return $this->getParameter('is_tracked');
    }

    /**
     * Set Is Buying Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setIsTracked($value) {
        return $this->setParameter('is_tracked', $value);
    }

    /**
     * Get Is Buying Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getIsBuying() {
        return $this->getParameter('is_buying');
    }

    /**
     * Set Is Buying Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setIsBuying($value) {
        return $this->setParameter('is_buying', $value);
    }

    /**
     * Get Is Buying Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getIsSelling() {
        return $this->getParameter('is_selling');
    }

    /**
     * Set Is Selling Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setIsSelling($value) {
        return $this->setParameter('is_selling', $value);
    }

    /**
     * Get Description Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getDescription() {
        return $this->getParameter('description');
    }

    /**
     * Set Description Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setDescription($value) {
        return $this->setParameter('description', $value);
    }

    /**
     * Get Buying Description Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getBuyingDescription() {
        return $this->getParameter('buying_description');
    }

    /**
     * Set Buying Description Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setBuyingDescription($value) {
        return $this->setParameter('buying_description', $value);
    }

    /**
     * Get Purchase Details Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getBuyingDetails() {
        return $this->getParameter('buying_details');
    }

    /**
     * Set Purchase Details Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setBuyingDetails($value) {
        return $this->setParameter('buying_details', $value);
    }

    /**
     * Get Sales Details Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getSalesDetails() {
        return $this->getParameter('sales_details');
    }

    /**
     * Get Asset Details Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getAssetDetails() {
        return $this->getParameter('asset_details');
    }

    /**
     * Set Asset Details Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setAssetDetails($value) {
        return $this->setParameter('asset_details', $value);
    }

    /**
     * Set Sales Details Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setSalesDetails($value) {
        return $this->setParameter('sales_details', $value);
    }

    public function addBuyingDetailsToItem(Item $item, $purchaseDetails) {
        if ($purchaseDetails) {
            $purchase = new Item\Purchase();
            if (array_key_exists('tracked_buying_account_code',$purchaseDetails)) {
                $purchase->setCOGSAccountCode($purchaseDetails['tracked_buying_account_code']);
            } else {
                $purchase->setAccountCode($purchaseDetails['buying_account_code']);
            }
            $purchase->setUnitPrice($purchaseDetails['buying_unit_price']);
            $purchase->setTaxType($purchaseDetails['buying_tax_type_id']);
            $item->setPurchaseDetails($purchase);
        }
    }

    public function addSalesDetailsToItem(Item $item, $salesDetails) {
        if ($salesDetails) {
            $sale = new Item\Sale();
            $sale->setAccountCode($salesDetails['selling_account_code']);
            $sale->setUnitPrice($salesDetails['selling_unit_price']);
            $sale->setTaxType($salesDetails['selling_tax_type_id']);
            $item->setSalesDetails($sale);
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getSalesDetailsData($data) {
        $data['UnitPrice'] = IndexSanityCheckHelper::indexSanityCheck('selling_unit_price', $data);
        $data['AccountCode'] = IndexSanityCheckHelper::indexSanityCheck('selling_account_code', $data);
        $data['TaxType'] = IndexSanityCheckHelper::indexSanityCheck('selling_tax_type_id', $data);

        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getBuyingDetailsData($data) {
        $data['UnitPrice'] = IndexSanityCheckHelper::indexSanityCheck('buying_unit_price', $data);
        $data['AccountCode'] = IndexSanityCheckHelper::indexSanityCheck('buying_account_code', $data);
        $data['TaxType'] = IndexSanityCheckHelper::indexSanityCheck('buying_tax_type_id', $data);
        $data['COGSAccountCode'] = IndexSanityCheckHelper::indexSanityCheck('tracked_buying_account_code', $data);

        return $data;
    }
}