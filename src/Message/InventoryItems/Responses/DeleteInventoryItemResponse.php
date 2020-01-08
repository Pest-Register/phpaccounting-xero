<?php

namespace PHPAccounting\Xero\Message\InventoryItems\Responses;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Delete InventoryItem(s) Response
 * @package PHPAccounting\XERO\Message\InventoryItems\Responses
 */
class DeleteInventoryItemResponse extends AbstractResponse
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
        }
        return null;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getInventoryItems(){
        return [];
    }
}