<?php

namespace PHPAccounting\Xero\Message\InventoryItems\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\InventoryItems\Responses\DeleteInventoryItemResponse;
use PHPAccounting\Xero\Traits\AccountingIDRequestTrait;
use XeroPHP\Models\Accounting\Item;
use XeroPHP\Remote\Exception;

/**
 * Delete Inventory Item
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class DeleteInventoryItemRequest extends AbstractXeroRequest
{
    use AccountingIDRequestTrait;

    public string $model = 'InventoryItem';

    /**
     * Set Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param string $value Contact Name
     * @return DeleteInventoryItemRequest
     */
    public function setStatus($value) {
        return  $this->setParameter('status', $value);
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        try {
            $this->validate('accounting_id');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }
    }


    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|DeleteInventoryItemResponse
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            $response = parent::handleRequestException($data, 'InvalidRequestException');
            return $this->createResponse($response);
        }
        try {
            $xero = $this->createXeroApplication();


            $item = new Item($xero);
            $item->setItemID($this->getAccountingID());

            $response = $xero->delete($item);

        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }
        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return DeleteInventoryItemResponse
     */
    public function createResponse($data)
    {
        return $this->response = new DeleteInventoryItemResponse($this, $data);
    }
}
