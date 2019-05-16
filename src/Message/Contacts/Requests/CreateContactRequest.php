<?php

use PHPAccounting\XERO\Message\Customers\Responses\CreateContactResponse;

class CreateContactRequest extends AbstractRequest
{
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
     */
    public function getData()
    {
        $this->validate('name');
        $data = [];

        $this->issetParam($data, 'Name', 'name');
        $this->issetParam($data, 'FirstName', 'first_name');
        $this->issetParam($data, 'LastName', 'first_name');
        $this->issetParam($data, 'EmailAddress', 'email_address');

        $data['Phones'] = $this->getPhoneData($this->getParameter('phones'));
        $data['Addresses'] = $this->getAddressData($this->getParameter('addresses'));

        if($this->getParameter('is_individual')) {
            $data['IsSupplier'] = false;
            $data['IsCustomer'] = true;
        }
        else {
            $data['IsSupplier'] = true;
            $data['IsCustomer'] = false;
        }

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
        return $this->response = new CreateContactResponse($this, $data, $headers);
    }


}