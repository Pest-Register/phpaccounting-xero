<?php

namespace PHPAccounting\Xero\Message\TaxRates\Requests;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\TaxRates\Responses\GetTaxRateResponse;
use XeroPHP\Models\Accounting\TaxRate;
use XeroPHP\Models\Accounting\TaxType;

/**
 * Get Tax Rate(s)
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class GetTaxRateRequest extends AbstractRequest
{

    /**
     * Set AccountingID from Parameter Bag (TaxRateID generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return GetTaxRateRequest
     */
    public function setAccountingIDs($value) {
        return $this->setParameter('accounting_ids', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return GetTaxRateRequest
     */
    public function setPage($value) {
        return $this->setParameter('page', $value);
    }

    /**
     * Return Comma Delimited String of Accounting IDs (TaxRateIDs)
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
     * @return GetTaxRateResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();


            if ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $accounts = $xero->loadByGUID(TaxRate::class, $this->getAccountingIDs());
                }
                else {
                    $accounts = $xero->loadByGUIDs(TaxRate::class, $this->getAccountingIDs());
                }
            } else {
                $accounts = $xero->load(TaxRate::class)->execute();
            }
            $response = $accounts;

        } catch (\Exception $exception){
            $response = [
                'status' => 'error',
                'detail' => $exception->getMessage()
            ];
        }
        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return GetTaxRateResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetTaxRateResponse($this, $data);
    }
}