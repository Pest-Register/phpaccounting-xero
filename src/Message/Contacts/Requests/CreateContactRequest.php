<?php

namespace PHPAccounting\Xero\Message\Contacts\Requests;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Contacts\Responses\CreateContactResponse;
use XeroPHP\Models\Accounting\Address;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\ContactGroup;
use XeroPHP\Models\Accounting\Phone;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

/**
 * Create Contact(s)
 * @package PHPAccounting\XERO\Message\Contacts\Requests
 */
class CreateContactRequest extends AbstractRequest
{
    /**
     * Set Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param string $value Contact Name
     * @return CreateContactRequest
     */
    public function setName($value){
        return $this->setParameter('name', $value);
    }

    /**
     * Set First Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param string $value Contact First Name
     * @return CreateContactRequest
     */
    public function setFirstName($value) {
        return $this->setParameter('first_name', $value);
    }

    /**
     * Set Last Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param string $value Contact Last Name
     * @return CreateContactRequest
     */
    public function setLastName($value) {
        return $this->setParameter('last_name', $value);
    }

    /**
     * Set Is Individual Boolean Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param string $value Contact Individual Status
     * @return CreateContactRequest
     */
    public function setIsIndividual($value) {
        return $this->setParameter('is_individual', $value);
    }

    /**
     * Set Email Address Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param string $value Contact Email Address
     * @return CreateContactRequest
     */
    public function setEmailAddress($value){
        return $this->setParameter('email_address', $value);
    }

    /**
     * Set Phones Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param array $value Array of Contact Phone Numbers
     * @return CreateContactRequest
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
     * @return CreateContactRequest
     */
    public function setAddresses($value){
        return $this->setParameter('addresses', $value);
    }

    /**
     * Set Contact Groups Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param array $value Array of Contact Groups
     * @return CreateContactRequest
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

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
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

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|CreateContactResponse
     */
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
                'detail' => $exception->getMessage()
            ];
            return $this->createResponse($response);
        }
        return $this->createResponse($response->getElements());
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return CreateContactResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateContactResponse($this, $data);
    }

}