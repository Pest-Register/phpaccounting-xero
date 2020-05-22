<?php

namespace PHPAccounting\Xero\Message\TaxRates\Requests;
use PHPAccounting\Xero\Message\AbstractRequest;
/**
 * Delete Inventory Item
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class DeleteTaxRateRequest extends AbstractRequest
{
    /**
     * Set AccountingID from Parameter Bag (InvoiceID generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return DeleteTaxRateRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (InvoiceID generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }


    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     */
    public function sendData($data)
    {
        return;
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     */
    public function createResponse($data)
    {
        return;
    }
}