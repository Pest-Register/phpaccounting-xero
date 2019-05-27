<?php

namespace PHPAccounting\Xero\Message\Contacts\Requests;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Contacts\Responses\CreateContactResponse;
use PHPAccounting\Xero\Message\Contacts\Responses\GetContactResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\Phone;

class CreateContactRequest extends AbstractRequest
{
    public function setName($value){
        return $this->setParameter('name', $value);
    }

    public function setFirstName($value) {
        return $this->setParameter('first_name', $value);
    }

    public function setLastName($value) {
        return $this->setParameter('last_name', $value);
    }

    public function setIsIndividual($value) {
        return $this->setParameter('is_individual', $value);
    }

    public function setEmailAddress($value){
        return $this->setParameter('email_address', $value);
    }

    public function setPhones($value){
        return $this->setParameter('phones', $value);
    }

    public function getPhones(){
        return $this->getParameter('phones');
    }

    public function setAddresses($value){
        return $this->setParameter('addresses', $value);
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
     * @param $contact
     * @param $phones
     */
    private function addPhonesToContact($contact, $phones) {
        foreach($phones as $phone) {
            $contact->addPhone($phone);
        }
    }

    /**
     * @param $data
     * @return array
     */
    public function getPhoneData($data) {
        $phones = [];
        foreach($data as $phoneType => $phoneNumber) {
            $newPhone = new Phone();
            $newPhone->setPhoneNumber($phoneNumber);
            switch ($phoneType) {
                case 'after_hours_phone':
                    $newPhone->setPhoneType('DDI');
                    break;
                case 'business_hours_phone':
                    $newPhone->setPhoneType('Business');
                    break;
                case 'mobile_phone':
                    $newPhone->setPhoneType('Mobile');
                    break;
            }
            array_push($phones, $newPhone);
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
        $this->issetParam('LastName', 'last_name');
        $this->issetParam('EmailAddress', 'email_address');
        $this->data['Phones'] = ($this->getPhones() != null ? $this->getPhoneData($this->getPhones()) : null);
//        $this->data['Addresses'] = ($this->getPhones() != null ? $this->getAddressData($this->getAddresses()) : null);

        return $this->data;
    }



    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();
            $xero->getOAuthClient()->setToken($this->getAccessToken());
            $xero->getOAuthClient()->setTokenSecret($this->getAccessTokenSecret());

            $contact = new Contact($xero);
            foreach ($data as $key => $value){
                if ($key === 'Phones') {
                    $this->addPhonesToContact($contact, $value);
                } else {
                    $methodName = 'set'. $key;
                    $contact->$methodName($value);
                }
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
        return $this->response = new CreateContactResponse($this, $data);
    }

}