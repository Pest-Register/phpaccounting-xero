<?php

namespace PHPAccounting\Xero\Message\TaxRates\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
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
     * @return array
     */
    public function getErrorMessage(){
        if ($this->data) {
            if(array_key_exists('status', $this->data)){
                return ErrorResponseHelper::parseErrorResponse(
                    isset($this->data['detail']) ? $this->data['detail'] : null,
                    isset($this->data['type']) ? $this->data['type'] : null,
                    isset($this->data['status']) ? $this->data['status'] : null,
                    isset($this->data['error_code']) ? $this->data['error_code'] : null,
                    isset($this->data['status_code']) ? $this->data['status_code'] : null,
                    isset($this->data['detail']) ? $this->data['detail'] : null,
                    $this->data,
                    'TaxRate');
            }
            if (count($this->data) === 0) {
                return [
                    'message' => 'NULL Returned from API or End of Pagination',
                    'exception' => 'NULL Returned from API or End of Pagination',
                    'error_code' => null,
                    'status_code' => null,
                    'detail' => null
                ];
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
        if ($this->data instanceof TaxRate){
            $taxRate = $this->data;
            $newTaxRate = [];
            $newTaxRate['accounting_id'] = $taxRate->getTaxType();
            $newTaxRate['code'] = $taxRate->getTaxType();
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
                $newTaxRate['accounting_id'] = $taxRate->getTaxType();
                $newTaxRate['code'] = $taxRate->getTaxType();
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