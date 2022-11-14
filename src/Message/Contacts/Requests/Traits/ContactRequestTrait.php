<?php

namespace PHPAccounting\Xero\Message\Contacts\Requests\Traits;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use XeroPHP\Models\Accounting\Address;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\ContactGroup;
use XeroPHP\Models\Accounting\Phone;

trait ContactRequestTrait
{
    /**
     * Set Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param string $value Contact Name
     */
    public function setName($value){
        return $this->setParameter('name', $value);
    }

    /**
     * Set First Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param string $value Contact First Name
     */
    public function setFirstName($value) {
        return $this->setParameter('first_name', $value);
    }

    /**
     * Set Last Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param string $value Contact Last Name
     */
    public function setLastName($value) {
        return $this->setParameter('last_name', $value);
    }

    /**
     * Set Is Individual Boolean Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param string $value Contact Individual Status
     */
    public function setIsIndividual($value) {
        return $this->setParameter('is_individual', $value);
    }

    /**
     * Set Email Address Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param string $value Contact Email Address
     */
    public function setEmailAddress($value){
        return $this->setParameter('email_address', $value);
    }

    /**
     * Set Phones Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param array $value Array of Contact Phone Numbers
     */
    public function setPhones($value){
        return $this->setParameter('phones', $value);
    }

    /**
     * Get Phones Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @return mixed
     */
    public function getPhones(){
        return $this->getParameter('phones');
    }

    /**
     * Set Addresses Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param array $value Array of Contact Addresses
     */
    public function setAddresses($value){
        return $this->setParameter('addresses', $value);
    }

    /**
     * Set Contact Groups Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param array $value Array of Contact Groups
     */
    public function setContactGroups($value) {
        return $this->setParameter('contact_groups', $value);
    }

    /**
     * Get ContactGroups Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @return mixed
     */
    public function getContactGroups() {
        return $this->getParameter('contact_groups');
    }

    /**
     * Get Addresses Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @return mixed
     */
    public function getAddresses(){
        return $this->getParameter('addresses');
    }


    /**
     * Set Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param $value
     * @return mixed
     */
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }


    /**
     * Get Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @return mixed
     */
    public function getStatus(){
        return $this->getParameter('status');
    }

    /**
     * Get Address Array with Address Details for Contact
     * @access public
     * @param array $data Array of Xero Addresses
     * @return array
     */
    public function getAddressData($data) {
        $addresses = [];
        foreach($data as $address) {
            $newAddress = new Address();
            $newAddress->setAddressType(IndexSanityCheckHelper::indexSanityCheck('type', $address));
            $newAddress->setAddressLine1(IndexSanityCheckHelper::indexSanityCheck('address_line_1', $address));
            $newAddress->setCity(IndexSanityCheckHelper::indexSanityCheck('city', $address));
            $newAddress->setRegion(IndexSanityCheckHelper::indexSanityCheck('state', $address));
            $newAddress->setPostalCode(IndexSanityCheckHelper::indexSanityCheck('postal_code', $address));
            $newAddress->setCountry(IndexSanityCheckHelper::indexSanityCheck('country', $address));
            array_push($addresses, $newAddress);
        }

        return $addresses;
    }

    /**
     * Add Contact Groups to Contact
     * @param Contact $contact Xero Contact Object
     * @param array $groups Array of Contact Group Objects
     */
    private function addGroupsToContact(Contact $contact, $groups) {
        if ($groups) {
            foreach($groups as $group) {
                $contact->addContactGroup($group);
            }
        }
    }

    /**
     * Add Addresses to Contact
     * @param Contact $contact Xero Contact Object
     * @param array $addresses Array of Address Objects
     */
    private function addAddressesToContact(Contact $contact, $addresses) {
        if ($addresses) {
            foreach($addresses as $address) {
                $contact->addAddress($address);
            }
        }
    }

    /**
     * Add Phones to Contact
     * @param Contact $contact Xero Contact Object
     * @param array $phones Array of Phone Objects
     */
    private function addPhonesToContact(Contact $contact, $phones) {
        if ($phones) {
            foreach($phones as $phone) {
                $contact->addPhone($phone);
            }
        }
    }

    /**
     * Get Contact Group Array with Contact Group Details for Contact
     * @access public
     * @param array $data Array of Xero Contact Groups
     * @return array
     */
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
     * Get Phones Array with Phone Details for Contact
     * @access public
     * @param array $data Array of Xero Phones
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
}