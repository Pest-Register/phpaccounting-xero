<?php

namespace PHPAccounting\Xero\Message\InventoryItems\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\InventoryItems\Requests\Traits\InventoryItemRequestTrait;
use PHPAccounting\Xero\Message\InventoryItems\Responses\CreateInventoryItemResponse;
use XeroPHP\Models\Accounting\Item;
use XeroPHP\Remote\Exception;

/**
 * Create Inventory Item
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class CreateInventoryItemRequest extends AbstractXeroRequest
{
    use InventoryItemRequestTrait;

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
            $this->validate('code');
        } catch (InvalidRequestException $exception) {
            return $exception;;
        }

        $this->issetParam('Code', 'code');
        $this->issetParam('Name', 'name');
        $this->issetParam('IsSold', 'is_selling');
        $this->issetParam('IsPurchased', 'is_buying');
        $this->issetParam('IsTrackedAsInventory', 'is_tracked');
        $this->issetParam('Description', 'description');
        $this->issetParam('PurchaseDescription', 'buying_description');
        $this->data['PurchaseDetails'] = ($this->getBuyingDetails() != null ? $this->getBuyingDetailsData($this->getBuyingDetails()) : null);
        $this->data['SalesDetails'] = ($this->getSalesDetails() != null ? $this->getSalesDetailsData($this->getSalesDetails()) : null);

        if ($this->getAssetDetails()) {
            $this->data['InventoryAssetAccountCode'] = $this->getAssetDetails()['asset_account_code'];
        }
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return CreateInventoryItemResponse
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
     * @return CreateInventoryItemResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateInventoryItemResponse($this, $data);
    }
}
