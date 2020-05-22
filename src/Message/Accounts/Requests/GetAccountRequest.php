<?php

namespace PHPAccounting\Xero\Message\Accounts\Requests;

use GuzzleHttp\Exception\ClientException;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Accounts\Responses\GetAccountResponse;
use XeroPHP\Exception;
use XeroPHP\Models\Accounting\Account;
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
     * @return GetAccountResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetAccountResponse($this, $data);
    }
}