<?php

namespace PHPAccounting\Xero\Message\TaxRates\Responses;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Delete InventoryItem(s) Response
 * @package PHPAccounting\XERO\Message\InventoryItems\Responses
 */
class DeleteTaxRateResponse extends AbstractResponse
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
            if (count($this->data) === 0) {
                return false;
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
                return $this->data['detail'];
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
        $items= [];

        return $items;
    }
}