<?php

namespace PHPAccounting\Xero\Message\Accounts\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Accounts\Responses\CreateAccountResponse;
use PHPAccounting\Xero\Message\Accounts\Responses\GetAccountResponse;
use PHPAccounting\Xero\Message\Accounts\Responses\UpdateAccountResponse;
use PHPAccounting\Xero\Message\Contacts\Requests\UpdateContactRequest;
use XeroPHP\Models\Accounting\Account;
use XeroPHP\Remote\Exception\UnauthorizedException;
use Calcinai\OAuth2\Client\Provider\Exception\XeroProviderException;
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
 * Update Account(s)
 * @package PHPAccounting\XERO\Message\Accounts\Requests
 */
class UpdateAccountRequest extends AbstractRequest
{
    /**
     * Get Code Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getCode(){
        return $this->getParameter('code');
    }

    /**
     * Set Code Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Code
     * @return UpdateAccountRequest
     */
    public function setCode($value){
        return $this->setParameter('code', $value);
    }

    /**
     * Get Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getName(){
        return $this->getParameter('name');
    }

    /**
     * Set Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Name
     * @return UpdateAccountRequest
     */
    public function setName($value){
        return $this->setParameter('name', $value);
    }

    /**
     * Get Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getType(){
        return $this->getParameter('type');
    }

    /**
     * Set Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Type
     * @return UpdateAccountRequest
     */
    public function setType($value){
        return $this->setParameter('type', $value);
    }

    /**
     * Get Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getStatus(){
        return $this->getParameter('status');
    }

    /**
     * Set Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Status
     * @return UpdateAccountRequest
     */
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }

    /**
     * Get Description Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getDescription(){
        return $this->getParameter('description');
    }

    /**
     * Set Description Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Description
     * @return UpdateAccountRequest
     */
    public function setDescription($value){
        return $this->setParameter('description', $value);
    }

    /**
     * Get Tax Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getTaxType(){
        return $this->getParameter('tax_type');
    }

    /**
     * Set Tax Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Tax Type
     * @return UpdateAccountRequest
     */
    public function setTaxType($value){
        return $this->setParameter('tax_type', $value);
    }

    /**
     * Get Enable Payments to Account Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getEnablePaymentsToAccount(){
        return $this->getParameter('enable_payments_to_account');
    }

    /**
     * Set Tax Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Enable Payments to Account
     * @return UpdateAccountRequest
     */
    public function setEnablePaymentsToAccount($value){
        return $this->setParameter('enable_payments_to_account', $value);
    }

    /**
     * Get Show Inexpense Claim Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getShowInexpenseClaims(){
        return $this->getParameter('show_inexpense_claims');
    }

    /**
     * Set Show Inexpense Claim Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Show Inexpense Claim
     * @return UpdateAccountRequest
     */
    public function setShowInexpenseClaims($value){
        return $this->setParameter('show_inexpense_claims', $value);
    }

    /**
     * Set Bank Account Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Show Inexpense Claim
     * @return UpdateAccountRequest
     */
    public function setBankAccountType($value){
        return $this->setParameter('bank_account_type', $value);
    }

    /**
     * Get Bank Account Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getBankAccountType(){
        return $this->getParameter('bank_account_type');
    }

    /**
     * Set Bank Account Number Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Show Inexpense Claim
     * @return UpdateAccountRequest
     */
    public function setBankAccountNumber($value){
        return $this->setParameter('bank_account_number', $value);
    }

    /**
     * Get Bank Account Number Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getBankAccountNumber(){
        return $this->getParameter('bank_account_number');
    }

    /**
     * Set AccountingID from Parameter Bag (AccountID generic interface)
     * @see https://developer.xero.com/documentation/api/accounts
     * @param $value
     * @return UpdateAccountRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (AccountID generic interface)
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }

    /**
     * Set Currency Code Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Show Inexpense Claim
     * @return UpdateAccountRequest
     */
    public function setCurrencyCode($value){
        return $this->setParameter('currency_code', $value);
    }

    /**
     * Get Bank Account Number Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getCurrencyCode(){
        return $this->getParameter('currency_code');
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
            return $exception;;
        }

        $this->issetParam('AccountID', 'accounting_id');
        $this->issetParam('Code', 'code');
        $this->issetParam('Name', 'name');
        $this->issetParam('Type', 'type');
        $this->issetParam('Status', 'status');
        $this->issetParam('Description', 'description');
        $this->issetParam('TaxType', 'tax_type');
        $this->issetParam('EnablePaymentsToAccount', 'enable_payments_to_account');
        $this->issetParam('ShowInexpenseClaims', 'show_inexpense_claims');
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|CreateAccountResponse
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            $response = [
                'status' => 'error',
                'type' => 'InvalidRequestException',
                'detail' => $data->getMessage(),
                'error_code' => $data->getCode(),
                'status_code' => $data->getCode(),
            ];
            return $this->createResponse($response);
        }
        try {
            $xero = $this->createXeroApplication();

            $account = new Account($xero);
            foreach ($data as $key => $value){
                if ($key === 'ShowInexpenseClaims') {
                    $methodName = 'setShowInexpenseClaim';
                    $account->$methodName($value);
                } else {
                    $methodName = 'set'. $key;
                    $account->$methodName($value);
                }

            }
            $response = $xero->save($account);
        } catch (BadRequestException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'BadRequest',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (UnauthorizedException|XeroProviderException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Unauthorized',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (ForbiddenException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Forbidden',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (ReportPermissionMissingException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'ReportPermissionMissingException',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (NotFoundException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotFound',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (InternalErrorException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Internal',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (NotImplementedException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotImplemented',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (RateLimitExceededException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'RateLimitExceeded',
                'rate_problem' => $exception->getRateLimitProblem(),
                'retry' => $exception->getRetryAfter(),
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (NotAvailableException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotAvailable',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (OrganisationOfflineException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'OrganisationOffline',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        }
        return $this->createResponse($response->getElements());
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return UpdateAccountResponse
     */
    public function createResponse($data)
    {
        return $this->response = new UpdateAccountResponse($this, $data);
    }
}
