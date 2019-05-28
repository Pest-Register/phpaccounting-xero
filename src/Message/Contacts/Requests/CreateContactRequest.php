<?php

namespace PHPAccounting\Xero\Message\Contacts\Requests;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Contacts\Responses\CreateContactResponse;
use XeroPHP\Models\Accounting\Address;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\ContactGroup;
use XeroPHP\Models\Accounting\Phone;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

class CreateContactRequest extends AbstractRequest
{
    /**
     * Getters and Setters for Parameter Bag
     * @param $value
     * @return CreateContactRequest
     */
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

    public function setContactGroups($value) {
        return $this->setParameter('contact_groups', $value);
    }

    public function getContactGroups() {
        return $this->getParameter('contact_groups');
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
            $newAddress = new Address();
            $newAddress->setAddressType(IndexSanityCheckHelper::indexSanityCheck('type', $address));
            $newAddress->setAddressLine1(IndexSanityCheckHelper::indexSanityCheck('address_line_1', $address));
            $newAddress->setCity(IndexSanityCheckHelper::indexSanityCheck('city', $address));
            $newAddress->setPostalCode(IndexSanityCheckHelper::indexSanityCheck('postal_code', $address));
            $newAddress->setCountry(IndexSanityCheckHelper::indexSanityCheck('country', $address));
            array_push($addresses, $newAddress);
        }

        return $addresses;
    }

    private function addGroupsToContact($contact, $groups) {
        if ($groups) {
            foreach($groups as $group) {
                $contact->addContactGroup($group);
            }
        }
    }

    /**
     * @param $contact
     * @param $addresses
     */
    private function addAddressesToContact($contact, $addresses) {
        if ($addresses) {
            foreach($addresses as $address) {
                $contact->addAddress($address);
            }
        }
    }

    /**
     * @param $contact
     * @param $phones
     */
    private function addPhonesToContact($contact, $phones) {
        if ($phones) {
            foreach($phones as $phone) {
                $contact->addPhone($phone);
            }
        }
    }
    private function getContactGroupData($data) {
        $groups = [];
        foreach($data as $group) {
                $newGroup = new ContactGroup();
                $newGroup->setName(IndexSanityCheckHelper::indexSanityCheck('name', $group));
                $newGroup->setContactGroupID(IndexSanityCheckHelper::indexSanityCheck('accounting_id', $group));
                array_push($groups, $newGroup);
        }

        return $groups;
    }
    /**
     * @param $data
     * @return array
     */
    public function getPhoneData($data) {
        $phones = [];
        foreach($data as $phone) {
            $newPhone = new Phone();
            $newPhone->setPhoneCountryCode(IndexSanityCheckHelper::indexSanityCheck('country_code', $phone));
            $newPhone->setPhoneAreaCode(IndexSanityCheckHelper::indexSanityCheck('area_code', $phone));
            $newPhone->setPhoneNumber(IndexSanityCheckHelper::indexSanityCheck('phone_number', $phone));
            switch (IndexSanityCheckHelper::indexSanityCheck('type',$phone)) {
                case 'BUSINESS':
                    $newPhone->setPhoneType('BUSINESS');
                    break;
                case 'MOBILE':
                    $newPhone->setPhoneType('MOBILE');
                    break;
                case 'DDI':
                    $newPhone->setPhoneType('DDI');
                    break;
                default:
                    $newPhone->setPhoneType('DEFAULT');
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
        $this->issetParam('FirstName', 'display_name');
        $this->issetParam('LastName', 'last_name');
        $this->issetParam('EmailAddress', 'email_address');
        $this->issetParam('Website', 'website');
        $this->issetParam('IsIndividual', 'is_individual');
        $this->issetParam('BankAccountDetails', 'bank_account_details');
        $this->issetParam('TaxNumber', 'tax_number');
        $this->issetParam('AccountsReceivableTaxType', 'accounts_receivable_tax_type');
        $this->issetParam('AccountsPayableTaxType', 'accounts_payable_tax_type');
        $this->issetParam('DefaultCurrency', 'default_currency');

        $this->data['Phones'] = ($this->getPhones() != null ? $this->getPhoneData($this->getPhones()) : null);
        $this->data['Addresses'] = ($this->getAddresses() != null ? $this->getAddressData($this->getAddresses()) : null);
        $this->data['ContactGroups'] = ($this->getContactGroups() != null ? $this->getContactGroupData($this->getContactGroups()) : null);
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
                } elseif ($key === 'Addresses') {
                    $this->addAddressesToContact($contact, $value);
                } elseif ($key === 'ContactGroups') {
                    $this->addGroupsToContact($contact, $value);
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