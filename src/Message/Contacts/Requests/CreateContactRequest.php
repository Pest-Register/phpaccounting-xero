<?php

use PHPAccounting\XERO\Message\Customers\Responses\CreateCustomerResponse;


/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 13/05/2019
 * Time: 3:24 PM
 */

class CreateContactRequest extends AbstractRequest
{

    public function getName(){
        return $this->getParameter('name');
    }

    public function getEmail() {
        return $this->getParameter('email');
    }

    public function getFirstName(){
        return $this->getParameter('first_name');
    }

    public function getLastName(){
        return $this->getParameter('last_name');
    }


    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $data = [];

        $data['Name'] = $this->getName();

        return $data;
    }

    public function sendData($data)
    {
        $response = parent::sendData($data);
        $this->createResponse($response->getData(), $response->getHeaders());
    }

    public function getEndpoint()
    {
        return $this->endpoint . '/Contacts';
    }

    public function createResponse($data, $headers = [])
    {
        return $this->response = new CreateCustomerResponse($this, $data, $headers);
    }


}