<?php

namespace PHPAccounting\Xero\Message\Contacts\Requests;


use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Contacts\Responses\DeleteContactResponse;
use XeroPHP\Models\Accounting\Contact;

class DeleteContactRequest extends AbstractRequest
{

    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }

    public function setStatus($value) {
        return  $this->setParameter('status', $value);
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
        $this->issetParam('ContactID', 'accounting_id');
        $this->data['ContactStatus'] = 'ARCHIVED';
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return DeleteContactResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();
            $xero->getOAuthClient()->setToken($this->getAccessToken());
            $xero->getOAuthClient()->setTokenSecret($this->getAccessTokenSecret());

            $contact = new Contact($xero);
            foreach ($data as $key => $value){
                $methodName = 'set'. $key;
                $contact->$methodName($value);
            }

            $response = $contact->save();

        } catch (\Exception $exception){
            $response = [
                'status' => 'error',
                'detail' => 'Exception when creating transaction: ', $exception->getMessage()
            ];
        }

        return $this->createResponse($response->getElements());
    }

    public function createResponse($data)

    {
        return $this->response = new DeleteContactResponse($this, $data);
    }
}