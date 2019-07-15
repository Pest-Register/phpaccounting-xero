<?php

namespace PHPAccounting\Xero\Message\TaxRates\Responses;

use Omnipay\Common\Message\AbstractResponse;
use XeroPHP\Models\Accounting\TaxRate;

/**
 * Get Tax Rate(s) Response
 * @package PHPAccounting\XERO\Message\TaxRate\Responses
 */
class GetTaxRateResponse extends AbstractResponse
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
        if ($this->data instanceof TaxRate){
            $taxRate = $this->data;
            $newTaxRate = [];
            $newTaxRate['name'] = $taxRate->getName();
            $newTaxRate['tax_type'] = $taxRate->getTaxType();
            $newTaxRate['rate'] = $taxRate->getEffectiveRate();
            $newTaxRate['is_asset'] = $taxRate->getCanApplyToAssets();
            $newTaxRate['is_equity'] = $taxRate->getCanApplyToEquity();
            $newTaxRate['is_expense'] = $taxRate->getCanApplyToExpenses();
            $newTaxRate['is_liability'] = $taxRate->getCanApplyToLiabilities();
            $newTaxRate['is_revenue'] = $taxRate->getCanApplyToRevenue();
            array_push($taxRates, $newTaxRate);

        } else {
            foreach ($this->data as $taxRate) {
                $newTaxRate = [];
                $newTaxRate['name'] = $taxRate->getName();
                $newTaxRate['tax_type'] = $taxRate->getTaxType();
                $newTaxRate['rate'] = $taxRate->getEffectiveRate();
                $newTaxRate['is_asset'] = $taxRate->getCanApplyToAssets();
                $newTaxRate['is_equity'] = $taxRate->getCanApplyToEquity();
                $newTaxRate['is_expense'] = $taxRate->getCanApplyToExpenses();
                $newTaxRate['is_liability'] = $taxRate->getCanApplyToLiabilities();
                $newTaxRate['is_revenue'] = $taxRate->getCanApplyToRevenue();
                array_push($taxRates, $newTaxRate);
            }
        }

        return $taxRates;
    }
}