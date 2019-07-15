<?php

namespace PHPAccounting\Xero\Message\TaxRates\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

/**
 * Create Inventory Item(s) Response
 * @package PHPAccounting\XERO\Message\InventoryItems\Responses
 */
class CreateTaxRateResponse extends AbstractResponse
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

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getTaxRates(){
        $taxRates = [];
        foreach ($this->data as $taxRate) {
            $newTaxRate = [];
            $newTaxRate['name'] = IndexSanityCheckHelper::indexSanityCheck('Name', $taxRate);
            $newTaxRate['tax_type'] = IndexSanityCheckHelper::indexSanityCheck('TaxType', $taxRate);
            $newTaxRate['rate'] = IndexSanityCheckHelper::indexSanityCheck('EffectiveRate', $taxRate);
            $newTaxRate['is_asset'] = IndexSanityCheckHelper::indexSanityCheck('CanApplyToAssets', $taxRate);
            $newTaxRate['is_equity'] = IndexSanityCheckHelper::indexSanityCheck('CanApplyToEquity', $taxRate);
            $newTaxRate['is_expense'] = IndexSanityCheckHelper::indexSanityCheck('CanApplyToExpenses', $taxRate);
            $newTaxRate['is_liability'] = IndexSanityCheckHelper::indexSanityCheck('CanApplyToLiabilities', $taxRate);
            $newTaxRate['is_revenue'] = IndexSanityCheckHelper::indexSanityCheck('CanApplyToRevenue', $taxRate);
            array_push($taxRates, $newTaxRate);
        }

        return $taxRates;
    }
}