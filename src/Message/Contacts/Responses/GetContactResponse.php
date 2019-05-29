<?php
namespace PHPAccounting\Xero\Message\Contacts\Responses;

use Omnipay\Common\Message\AbstractResponse;
use XeroPHP\Models\Accounting\Phone;

/**
 * Class GetContactResponse
 * @package PHPAccounting\Xero\Message\Contacts\Responses
 */
class GetContactResponse extends AbstractResponse
{

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        if(array_key_exists('status', $this->data)){
            return !$this->data['status'] == 'error';
        }
        return true;
    }

    public function getErrorMessage(){
        if(array_key_exists('status', $this->data)){
            return $this->data['detail'];
        }
        return null;
    }

    /**
     * Create Generic Contact Groups if Valid
     * @param $data
     * @param $contact
     * @return mixed
     */
    private function parseContactGroups($data, $contact) {
        $contact['contact_groups'] = [];
        if ($data) {
            $contactGroups = [];
            foreach($data as $contactGroup) {
                $newContactGroup = [];
                $newContactGroup['accounting_id'] = $contactGroup->getContactGroupID();
                $newContactGroup['name'] = $contactGroup->getName();
                $newContactGroup['status'] = $contactGroup->getStatus();
                array_push($contactGroups, $newContactGroup);
            }
            $contact['contact_groups'] = $contactGroups;
        }

        return $contact;
    }
    /**
     * Create Generic Addresses if Valid
     * @param $data
     * @param $contact
     * @return mixed
     */
    private function parseAddresses($data, $contact) {
        $contact['addresses'] = [];
        if ($data) {
            $addresses = [];
            foreach($data as $address) {
                $newAddress = [];
                $newAddress['address_type'] = $address->getAddressType();
                $newAddress['address_line_1'] = $address->getAddressLine1();
                $newAddress['city'] = $address->getCity();
                $newAddress['postal_code'] = $address->getPostalCode();
                $newAddress['country'] = $address->getCountry();
                array_push($addresses, $newAddress);
            }
            $contact['addresses'] = $addresses;
        }

        return $contact;
    }

    /**
     * Create Generic Phones if Valid
     * @param $data
     * @param $contact
     * @return mixed
     */
    private function parsePhones($data, $contact) {
        $contact['phones'] = [];
        if ($data) {
            $phones = [];
            foreach($data as $phone) {
                $phoneType = $phone->getPhoneType();
                $phoneCountryCode = $phone->getPhoneCountryCode();
                $phoneAreaCode = $phone->getPhoneAreaCode();
                $phoneNumberRaw = $phone->getPhoneNumber();
                $phoneNumber = $phoneCountryCode.$phoneAreaCode.$phoneNumberRaw;
                if ($phoneNumber !== '') {
                    $newPhone = [];
                    $newPhone['type'] = $phoneType;
                    $newPhone['phone_number'] = $phoneNumberRaw;
                    $newPhone['area_code'] = $phoneAreaCode;
                    $newPhone['country_code'] = $phoneCountryCode;
                    array_push($phones, $newPhone);
                }

            }
            $contact['phones'] = $phones;
        }

        return $contact;
    }

    /**
     * Return all Contacts with Generic Schema Variable Assignment
     * @return array
     */
    public function getContacts(){
        $contacts = [];
        foreach ($this->data as $contact) {
            $newContact = [];
            $newContact['accounting_id'] = $contact->getContactID();
            $newContact['display_name'] = $contact->getFirstName();
            $newContact['last_name'] = $contact->getLastName();
            $newContact['email_address'] = $contact->getEmailAddress();
            $newContact['website'] = $contact->getWebsite();
            $newContact['type'] = ($contact->getIsSupplier() ? 'SUPPLIER' : 'CUSTOMER');
            $newContact['is_individual'] = !$contact->getIsSupplier();
            $newContact['bank_account_details'] = $contact->getBankAccountDetails();
            $newContact['tax_number'] = $contact->getTaxNumber();
            $newContact['accounts_receivable_tax_type'] = $contact->getAccountsReceivableTaxType();
            $newContact['accounts_payable_tax_type'] = $contact->getAccountsPayableTaxType();
            $newContact['default_currency'] = $contact->getDefaultCurrency();
            $newContact = $this->parseContactGroups($contact->getContactGroups(), $newContact);
            $newContact = $this->parsePhones($contact->getPhones(), $newContact);
            $newContact = $this->parseAddresses($contact->getAddresses(), $newContact);
            array_push($contacts, $newContact);
        }

        return $contacts;
    }
}