<?php

namespace PHPAccounting\Xero\Message\Accounts\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Accounts\Responses\DeleteAccountResponse;
use PHPAccounting\Xero\Traits\AccountingIDRequestTrait;
use XeroPHP\Models\Accounting\Account;
use XeroPHP\Remote\Exception;

/**
 * Delete Account(s)
 * @package PHPAccounting\XERO\Message\Accounts\Requests
 */
class DeleteAccountRequest extends AbstractXeroRequest
{
    use AccountingIDRequestTrait;

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
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|DeleteAccountResponse
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
                $methodName = 'set'. $key;
                $account->$methodName($value);
            }

            $account->setStatus('ARCHIVED');

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
     * @return DeleteAccountResponse
     */
    public function createResponse($data)
    {
        return $this->response = new DeleteAccountResponse($this, $data);
    }
}
