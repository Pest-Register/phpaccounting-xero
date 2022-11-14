<?php

namespace PHPAccounting\Xero\Message\Accounts\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Accounts\Requests\Traits\AccountRequestTrait;
use PHPAccounting\Xero\Message\Accounts\Responses\CreateAccountResponse;
use PHPAccounting\Xero\Message\Accounts\Responses\UpdateAccountResponse;
use PHPAccounting\Xero\Traits\AccountingIDRequestTrait;
use XeroPHP\Models\Accounting\Account;
use XeroPHP\Remote\Exception;

/**
 * Update Account(s)
 * @package PHPAccounting\XERO\Message\Accounts\Requests
 */
class UpdateAccountRequest extends AbstractXeroRequest
{
    use AccountRequestTrait, AccountingIDRequestTrait;

    public string $model = 'Account';

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

        $this->issetParam('AccountID', 'accounting_id');
        $this->issetParam('Code', 'code');
        $this->issetParam('Name', 'name');
        $this->issetParam('Type', 'type');
        $this->issetParam('Status', 'status');
        $this->issetParam('Description', 'description');
        $this->issetParam('TaxType', 'tax_type_id');
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
            $response = parent::handleRequestException($data, 'InvalidRequestException');
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
        } catch(Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
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
