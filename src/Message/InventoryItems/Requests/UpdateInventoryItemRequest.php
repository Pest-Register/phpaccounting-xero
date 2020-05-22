<?php

namespace PHPAccounting\Xero\Message\InventoryItems\Requests;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\InventoryItems\Responses\UpdateInventoryItemResponse;
use XeroPHP\Models\Accounting\Item;
use XeroPHP\Remote\Exception\UnauthorizedException;
use XeroPHP\Remote\Exception\BadRequestException;
use XeroPHP\Remote\Exception\ForbiddenException;
use XeroPHP\Remote\Exception\ReportPermissionMissingException;
use XeroPHP\Remote\Exception\NotFoundException;
use XeroPHP\Remote\Exception\InternalErrorException;
use XeroPHP\Remote\Exception\NotImplementedException;
use XeroPHP\Remote\Exception\RateLimitExceededException;
use XeroPHP\Remote\Exception\NotAvailableException;
use XeroPHP\Remote\Exception\OrganisationOfflineException;
/**
 * Update Inventory Item(s)
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class UpdateInventoryItemRequest extends AbstractRequest
{

    /**
     * Set AccountingID from Parameter Bag (ItemId generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return UpdateInventoryItemRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (ItemId generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }

    /**
     * Get Code Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getCode(){
        return $this->getParameter('code');
    }

    /**
     * Set Code Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param string $value Account Code
     * @return UpdateInventoryItemRequest
     */
    public function setCode($value){
        return $this->setParameter('code', $value);
    }

    /**
     * Get Inventory Asset AccountCode Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getInventoryAccountCode() {
        return $this->getParameter('inventory_account_code');
    }

    /**
     * Set Inventory Asset AccountCode Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setInventoryAccountCode($value) {
        return $this->setParameter('inventory_account_code', $value);
    }

    /**
     * Get Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getName() {
        return $this->getParameter('name');
    }

    /**
     * Set Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setName($value) {
        return $this->setParameter('name', $value);
    }

    /**
     * Get Is Buying Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getIsBuying() {
        return $this->getParameter('is_buying');
    }

    /**
     * Set Is Buying Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setIsBuying($value) {
        return $this->setParameter('is_buying', $value);
    }

    /**
     * Get Is Buying Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getIsSelling() {
        return $this->getParameter('is_selling');
    }

    /**
     * Set Is Selling Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setIsSelling($value) {
        return $this->setParameter('is_selling', $value);
    }

    /**
     * Get Description Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getDescription() {
        return $this->getParameter('description');
    }

    /**
     * Set Description Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setDescription($value) {
        return $this->setParameter('description', $value);
    }

    /**
     * Get Buying Description Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getBuyingDescription() {
        return $this->getParameter('buying_description');
    }

    /**
     * Set Buying Description Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setBuyingDescription($value) {
        return $this->setParameter('buying_description', $value);
    }

    /**
     * Get Purchase Details Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getBuyingDetails() {
        return $this->getParameter('buying_details');
    }

    /**
     * Set Purchase Details Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setBuyingDetails($value) {
        return $this->setParameter('buying_details', $value);
    }

    /**
     * Get Sales Details Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @return mixed
     */
    public function getSalesDetails() {
        return $this->getParameter('sales_details');
    }

    /**
     * Set Sales Details Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/items
     * @param $value
     * @return mixed
     */
    public function setSalesDetails($value) {
        return $this->setParameter('sales_details', $value);
    }

    public function addBuyingDetailsToItem(Item $item, $purchaseDetails) {
        if ($purchaseDetails) {
            $purchase = new Item\Purchase();
            if (array_key_exists('tracked_buying_account_code',$purchaseDetails)) {
                $purchase->setCOGSAccountCode($purchaseDetails['tracked_buying_account_code']);
            } else {
                $purchase->setAccountCode($purchaseDetails['buying_account_code']);
            }
            $purchase->setUnitPrice($purchaseDetails['buying_unit_price']);
            $purchase->setTaxType($purchaseDetails['buying_tax_type_code']);
            $item->setPurchaseDetails($purchase);
        }
    }

    public function addSalesDetailsToItem(Item $item, $salesDetails) {
        if ($salesDetails) {
            $sale = new Item\Sale();
            $sale->setAccountCode($salesDetails['selling_account_code']);
            $sale->setUnitPrice($salesDetails['selling_unit_price']);
            $sale->setTaxType($salesDetails['selling_tax_type_code']);
            $item->setSalesDetails($sale);
        }
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getSalesDetailsData($data) {
        $data['UnitPrice'] = IndexSanityCheckHelper::indexSanityCheck('selling_unit_price', $data);
        $data['AccountCode'] = IndexSanityCheckHelper::indexSanityCheck('selling_account_code', $data);
        $data['TaxType'] = IndexSanityCheckHelper::indexSanityCheck('selling_tax_type_code', $data);

        return $data;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getBuyingDetailsData($data) {
        $data['UnitPrice'] = IndexSanityCheckHelper::indexSanityCheck('buying_unit_price', $data);
        $data['AccountCode'] = IndexSanityCheckHelper::indexSanityCheck('buying_account_code', $data);
        $data['TaxType'] = IndexSanityCheckHelper::indexSanityCheck('buying_tax_type_code', $data);
        $data['COGSAccountCode'] = IndexSanityCheckHelper::indexSanityCheck('tracked_buying_account_code', $data);

        return $data;
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
        } catch (BadRequestException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'BadRequest',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (UnauthorizedException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Unauthorized',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (ForbiddenException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Forbidden',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (ReportPermissionMissingException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'ReportPermissionMissingException',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (NotFoundException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotFound',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (InternalErrorException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Internal',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (NotImplementedException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotImplemented',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (RateLimitExceededException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'RateLimitExceeded',
                'rate_problem' => $exception->getRateLimitProblem(),
                'retry' => $exception->getRetryAfter(),
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (NotAvailableException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotAvailable',
                'detail' => $exception->getMessage()
            ];

            return $this->createResponse($response);
        } catch (OrganisationOfflineException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'OrganisationOffline',
                'detail' => $exception->getMessage()
            ];

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