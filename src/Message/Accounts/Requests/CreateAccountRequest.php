<?php

namespace PHPAccounting\Xero\Message\Accounts\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Accounts\Requests\Traits\AccountRequestTrait;
use PHPAccounting\Xero\Message\Accounts\Responses\CreateAccountResponse;
use PHPAccounting\Xero\Message\Traits\AccountingIDRequestTrait;
use XeroPHP\Models\Accounting\Account;
use XeroPHP\Remote\Exception;

/**
 * Create Account(s)
 * @package PHPAccounting\XERO\Message\Accounts\Requests
 */
class CreateAccountRequest extends AbstractXeroRequest
{
    use AccountRequestTrait;

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
            $this->validate('code', 'name', 'type');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('Code', 'code');
        $this->issetParam('Name', 'name');
        $this->issetParam('Type', 'type');
        $this->issetParam('Status', 'status');
        $this->issetParam('Description', 'description');
        $this->issetParam('TaxType', 'tax_type_id');
        $this->issetParam('EnablePaymentsToAccount', 'enable_payments_to_account');
        $this->issetParam('ShowInexpenseClaims', 'show_inexpense_claims');
        $this->issetParam('BankAccountType', 'bank_account_type');
        $this->issetParam('BankAccountNumber', 'bank_account_number');
        $this->issetParam('CurrencyCode', 'currency_code');
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
            foreach ($data as $key => $value) {
                if ($key === 'ShowInexpenseClaims') {
                    $methodName = 'setShowInexpenseClaim';
                    $account->$methodName($value);
                } else {
                    $methodName = 'set' . $key;
                    $account->$methodName($value);
                }

            }
            $response = $xero->save($account);

        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }
        return $this->createResponse($response->getElements());
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return CreateAccountResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateAccountResponse($this, $data);
    }
}
