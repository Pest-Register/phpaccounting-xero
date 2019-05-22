<?php

namespace PHPAccounting\Xero\Message\Contacts\Requests;
use PHPAccounting\Xero\Message\Contacts\Responses\GetContactResponse;

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
        $this->issetParam('ContactID', 'contact_id');
        return $this->data;
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return void
     */

    public function sendData($data)
    {
        $response = parent::sendData($data);
        $this->createResponse($response->getData(), $response->getHeaders());
    }

    public function getEndpoint()
    {
        //check if they are singling out a user or returning all of them
        if(array_key_exists('ContactID', $this->data)){
            return $this->endpoint. '/Contacts/'. $this->data['ContactID'];
        }
        return $this->endpoint . '/Contacts';
    }

    public function createResponse($data, $headers = [])
    {
        return $this->response = new GetContactResponse($this, $data, $headers);
    }

}