<?php

namespace PHPAccounting\Xero\Message\Contacts\Requests;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Contacts\Responses\CreateContactResponse;

class CreateContactRequest extends AbstractRequest
{
    public function setName($value){
        return $this->setParameter('name', $value);
    }

    public function setPhones($value){
        return $this->setParameter('phones', $value);
    }

    public function setAddresses($value){
        return $this->setParameter('addresses', $value);
    }

    public function getPhones(){
        return $this->getParameter('phones');
    }

    public function getAddresses(){
        return $this->getParameter('addresses');
    }
    /**
     * @param $data
     * @return array
     */
    public function getAddressData($data) {
        $addresses = [];
        foreach($data as $address) {
            $newAddress = [];
            $newAddress['AddressType'] = $address->address_type;
            $newAddress['AddressLine1'] = $address->address_line_1;
            $newAddress['City'] = $address->city;
            $newAddress['PostalCode'] = $address->postal_code;
            $newAddress['Country'] = $address->country;
            array_push($addresses, $newAddress);
        }

        return $addresses;
    }

    /**
     * @param $data
     * @return array
     */
    public function getPhoneData($data) {
        $phones = [];
        foreach($data as $phone) {
            $newPhone = [];
            $newPhone['PhoneType'] = $phone->phone_type;
            $newPhone['PhoneNumber'] = $phone->phone_number;
        }

        return $phones;
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \PHPAccounting\Common\Exception\InvalidRequestException
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('name');


        $this->issetParam('Name', 'name');
        $this->issetParam('FirstName', 'first_name');
        $this->issetParam('LastName', 'first_name');
        $this->issetParam('EmailAddress', 'email_address');

        $this->data['Phones'] = ($this->getPhones() != null ? $this->getPhoneData($this->getPhones()) : null);
        $this->data['Addresses'] = ($this->getPhones() != null ? $this->getAddressData($this->getAddresses()) : null);

        if($this->getParameter('is_individual')) {
            $this->data['IsSupplier'] = false;
            $this->data['IsCustomer'] = true;
        }
        else {
            $this->data['IsSupplier'] = true;
            $this->data['IsCustomer'] = false;
        }

        return $this->data;
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
        return $this->response = new CreateContactResponse($this, $data, $headers);
    }


}