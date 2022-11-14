<?php

namespace PHPAccounting\Xero\Message\InventoryItems\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\InventoryItems\Requests\Traits\InventoryItemRequestTrait;
use PHPAccounting\Xero\Message\InventoryItems\Responses\UpdateInventoryItemResponse;
use PHPAccounting\Xero\Message\Traits\AccountingIDRequestTrait;
use XeroPHP\Models\Accounting\Item;
use XeroPHP\Remote\Exception;

/**
 * Update Inventory Item(s)
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class UpdateInventoryItemRequest extends AbstractXeroRequest
{
    use InventoryItemRequestTrait, AccountingIDRequestTrait;

    public string $model = 'InventoryItem';

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
            return $exception;;
        }

        $this->issetParam('Code', 'code');
        $this->issetParam('ItemId', 'accounting_id');
        $this->issetParam('InventoryAssetAccountCode', 'inventory_account_code');
        $this->issetParam('Name', 'name');
        $this->issetParam('IsSold', 'is_selling');
        $this->issetParam('IsPurchased', 'is_buying');
        $this->issetParam('Description', 'description');
        $this->issetParam('PurchaseDescription', 'buying_description');
        $this->data['PurchaseDetails'] = ($this->getBuyingDetails() != null ? $this->getBuyingDetailsData($this->getBuyingDetails()) : null);
        $this->data['SalesDetails'] = ($this->getSalesDetails() != null ? $this->getSalesDetailsData($this->getSalesDetails()) : null);

        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return UpdateInventoryItemResponse
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
            foreach ($data as $key => $value){
                if ($key === 'PurchaseDetails') {
                    $this->addBuyingDetailsToItem($item, $value);
                } elseif ($key === 'SalesDetails') {
                    $this->addSalesDetailsToItem($item, $value);
                } else {
                    $methodName = 'set'. $key;
                    $item->$methodName($value);
                }

            }
            $response = $xero->save($item);
        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }
        return $this->createResponse($response->getElements());
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return UpdateInventoryItemResponse
     */
    public function createResponse($data)
    {
        return $this->response = new UpdateInventoryItemResponse($this, $data);
    }

}
