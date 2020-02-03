<?php

namespace PHPAccounting\Xero\Message\InventoryItems\Requests;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\InventoryItems\Responses\GetInventoryItemResponse;
use XeroPHP\Models\Accounting\Item;

/**
 * Get Inventory Items(s)
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class GetInventoryItemRequest extends AbstractRequest
{

    /**
     * Set AccountingID from Parameter Bag (InvoiceID generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return GetInventoryItemRequest
     */
    public function setAccountingIDs($value) {
        return $this->setParameter('accounting_ids', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return GetInventoryItemRequest
     */
    public function setPage($value) {
        return $this->setParameter('page', $value);
    }

    /**
     * Return Comma Delimited String of Accounting IDs (ContactGroupIDs)
     * @return mixed comma-delimited-string
     */
    public function getAccountingIDs() {
        if ($this->getParameter('accounting_ids')) {
            return implode(', ',$this->getParameter('accounting_ids'));
        }
        return null;
    }

    /**
     * Return Page Value for Pagination
     * @return integer
     */
    public function getPage() {
        if ($this->getParameter('page')) {
            return $this->getParameter('page');
        }

        return 1;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return GetInventoryItemResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();


            if ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $accounts = $xero->loadByGUID(Item::class, $this->getAccountingIDs());
                }
                else {
                    $accounts = $xero->loadByGUIDs(Item::class, $this->getAccountingIDs());
                }
            } else {
                $accounts = $xero->load(Item::class)->execute();
            }
            $response = $accounts;

        } catch (\Exception $exception){
            $contents = $exception->getResponse()->getBody()->getContents();
            if (json_decode($contents, 1)) {
                $response = [
                    'status' => 'error',
                    'detail' => json_decode($contents, 1)['detail']
                ];
            } elseif (simplexml_load_string($contents)) {
                $message = json_decode(json_encode(simplexml_load_string($contents)))->Elements->DataContractBase->ValidationErrors->ValidationError->Message;
                $response = [
                    'status' => 'error',
                    'detail' => $message
                ];
            }
        }
        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return GetInventoryItemResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetInventoryItemResponse($this, $data);
    }
}