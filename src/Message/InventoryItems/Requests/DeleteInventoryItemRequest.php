<?php

namespace PHPAccounting\Xero\Message\InventoryItems\Requests;

use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\InventoryItems\Responses\DeleteInventoryItemResponse;
use XeroPHP\Models\Accounting\Item;

/**
 * Delete Inventory Item
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class DeleteInventoryItemRequest extends AbstractRequest
{
    /**
     * Set AccountingID from Parameter Bag (InvoiceID generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return DeleteInventoryItemRequest
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
        $this->validate('accounting_id');
    }


    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|DeleteInventoryItemResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();


            $item = new Item($xero);
            $item->setItemID($this->getAccountingID());

            $response = $item->delete();

        } catch (\Exception $exception){
            $contents = $exception->getResponse()->getBody()->getContents();
            if (json_decode($contents, 1)) {
                $response = [
                    'status' => 'error',
                    'detail' => json_decode($contents, 1)['detail']
                ];
            } elseif (simplexml_load_string($contents)) {
                $response = [
                    'status' => 'error',
                    'detail' => json_decode(json_encode(simplexml_load_string($contents)))['Message']
                ];
            }
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