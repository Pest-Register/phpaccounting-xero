<?php

namespace PHPAccounting\Xero\Message\TaxRates\Responses;

use PHPAccounting\Xero\Message\AbstractXeroResponse;
use XeroPHP\Models\Accounting\TaxRate;

/**
 * Get Tax Rate(s) Response
 * @package PHPAccounting\XERO\Message\TaxRate\Responses
 */
class GetTaxRateResponse extends AbstractXeroResponse
{
    private function parseData($taxRate) {
        $newTaxRate = [];
        $newTaxRate['accounting_id'] = $taxRate->getTaxType();
        $newTaxRate['code'] = $taxRate->getTaxType();
        $newTaxRate['name'] = $taxRate->getName();
        $newTaxRate['tax_type_id'] = $taxRate->getTaxType();
        $newTaxRate['rate'] = $taxRate->getEffectiveRate();
        $newTaxRate['is_asset'] = $taxRate->getCanApplyToAssets();
        $newTaxRate['is_equity'] = $taxRate->getCanApplyToEquity();
        $newTaxRate['is_expense'] = $taxRate->getCanApplyToExpenses();
        $newTaxRate['is_liability'] = $taxRate->getCanApplyToLiabilities();
        $newTaxRate['is_revenue'] = $taxRate->getCanApplyToRevenue();

        return $newTaxRate;
    }
    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getTaxRates(){
        $taxRates = [];
        if ($this->data instanceof TaxRate){
            $newTaxRate = $this->parseData($this->data);
            array_push($taxRates, $newTaxRate);

        } else {
            foreach ($this->data as $taxRate) {
                $newTaxRate = $this->parseData($taxRate);
                array_push($taxRates, $newTaxRate);
            }
        }

        return $taxRates;
    }
}