<?php

namespace PHPAccounting\Xero\Message\TaxRates\Responses;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;

/**
 * Update Inventory Item(s) Response
 * @package PHPAccounting\XERO\Message\InventoryItems\Responses
 */
class UpdateTaxRateResponse extends AbstractXeroResponse
{

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getTaxRates(){
        $taxRates = [];
        foreach ($this->data as $taxRate) {
            $newTaxRate = [];
            $newTaxRate['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('TaxType', $taxRate);
            $newTaxRate['code'] = IndexSanityCheckHelper::indexSanityCheck('TaxType', $taxRate);
            $newTaxRate['name'] = IndexSanityCheckHelper::indexSanityCheck('Name', $taxRate);
            $newTaxRate['tax_type_id'] = IndexSanityCheckHelper::indexSanityCheck('TaxType', $taxRate);
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