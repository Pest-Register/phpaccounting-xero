<?php

namespace PHPAccounting\Xero\Message\TaxRates\Requests;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Traits\AccountingIDRequestTrait;

/**
 * Delete Inventory Item
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class DeleteTaxRateRequest extends AbstractXeroRequest
{
    use AccountingIDRequestTrait;

    public string $model = 'TaxRate';

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