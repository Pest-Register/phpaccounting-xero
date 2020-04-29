<?php

namespace PHPAccounting\Xero\Message\TaxRates\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
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
                return ErrorResponseHelper::parseErrorResponse($this->data['detail']);
            }
            if (count($this->data) === 0) {
                return 'NULL Returned from API or End of Pagination';
            }
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