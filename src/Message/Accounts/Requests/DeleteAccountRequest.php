<?php

namespace PHPAccounting\Xero\Message\Accounts\Requests;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Accounts\Responses\DeleteAccountResponse;
use PHPAccounting\Xero\Message\Invoices\Requests\DeletePaymentRequest;
use PHPAccounting\Xero\Message\Invoices\Responses\DeletePaymentResponse;
use XeroPHP\Models\Accounting\Account;
use XeroPHP\Models\Accounting\Invoice;


/**
 * Delete Account(s)
 * @package PHPAccounting\XERO\Message\Accounts\Requests
 */
class DeleteAccountRequest extends AbstractRequest
{
    /**
     * Set AccountingID from Parameter Bag (AccountID generic interface)
     * @see https://developer.xero.com/documentation/api/accounts
     * @param $value
     * @return DeleteAccountRequest
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
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('accounting_id');
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
        try {
            $xero = $this->createXeroApplication();

            $account = new Account($xero);
            foreach ($data as $key => $value){
                $methodName = 'set'. $key;
                $account->$methodName($value);
            }

            $account->setStatus('ARCHIVED');

            $response = $account->save();

        } catch (\Exception $exception){
            $contents = $exception->getResponse()->getBody()->getContents();
            $contentsObj = json_decode($contents, 1);

            if ($contentsObj) {
                $response = [
                    'status' => 'error',
                    'detail' => $contentsObj['detail']
                ];
            } elseif (simplexml_load_string($contents)) {
                $error = json_decode(json_encode(simplexml_load_string($contents)))->Elements->DataContractBase->ValidationErrors->ValidationError;
                if (is_array($error)) {
                    $message = $error[0]->Message;
                } else {
                    $message = $error->Message;
                }
                $response = [
                    'status' => 'error',
                    'detail' => $message
                ];
            }
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