<?php

namespace PHPAccounting\Xero\Message\Contacts\Requests;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Contacts\Responses\GetContactResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Remote\Request;

class GetContactRequest extends AbstractRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $this->issetParam('ContactID', 'accounting_id');
        return $this->data;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return GetContactResponse
     */

    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();
            $xero->getOAuthClient()->setToken($this->getAccessToken());
            $xero->getOAuthClient()->setTokenSecret($this->getAccessTokenSecret());

            $contacts = $xero->load(Contact::class)
                ->where('ContactStatus', Contact::CONTACT_STATUS_ACTIVE)
                ->execute();
            $response = $contacts;

        } catch (\Exception $exception){
            $response = [
                'status' => 'error',
                'detail' => 'Exception when creating transaction: ', $exception->getMessage()
            ];
        }

        return $this->createResponse($response);
    }

    public function createResponse($data)
    {
        return $this->response = new GetContactResponse($this, $data);
    }

}