<?php

namespace PHPAccounting\Xero\Message\Accounts\Requests;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Accounts\Responses\GetAccountResponse;
use XeroPHP\Models\Accounting\Account;


/**
 * Get Account(s)
 * @package PHPAccounting\XERO\Message\Accounts\Requests
 */
class GetAccountRequest extends AbstractRequest
{
    /**
     * Set AccountingID from Parameter Bag (AccountID generic interface)
     * @see https://developer.xero.com/documentation/api/accounts
     * @param $value
     * @return GetAccountRequest
     */
    public function setAccountingIDs($value) {
        return $this->setParameter('accounting_ids', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param $value
     * @return GetAccountRequest
     */
    public function setPage($value) {
        return $this->setParameter('page', $value);
    }

    /**
     * Return Comma Delimited String of Accounting IDs (AccountIDs)
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
        return $this->getParameter('page');
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|GetAccountResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();

            if ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $accounts = $xero->loadByGUID(Account::class, $this->getAccountingIDs());
                }
                else {
                    $accounts = $xero->loadByGUIDs(Account::class, $this->getAccountingIDs());
                }
            } else {
                $accounts = $xero->load(Account::class)->execute();
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
     * @return GetAccountResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetAccountResponse($this, $data);
    }
}