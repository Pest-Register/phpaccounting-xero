<?php

namespace PHPAccounting\Xero\Message\Contacts\Responses;
use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

class CreateContactResponse extends AbstractResponse
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
            return $this->data[0];
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
                $newContactGroup['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('ContactGroupID',$contactGroup);
                $newContactGroup['name'] = IndexSanityCheckHelper::indexSanityCheck('Name',$contactGroup);
                $newContactGroup['status'] = IndexSanityCheckHelper::indexSanityCheck('Status',$contactGroup);
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
                $newAddress['address_type'] =  IndexSanityCheckHelper::indexSanityCheck('AddressType',$address);
                $newAddress['address_line_1'] = IndexSanityCheckHelper::indexSanityCheck('AddressLine1',$address);;
                $newAddress['city'] = IndexSanityCheckHelper::indexSanityCheck('City',$address);
                $newAddress['postal_code'] = IndexSanityCheckHelper::indexSanityCheck('PostalCode',$address);
                $newAddress['country'] = IndexSanityCheckHelper::indexSanityCheck('Country',$address);
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
        if ($data) {
            $phones = [];
            foreach($data as $phone) {
                $phoneType = IndexSanityCheckHelper::indexSanityCheck('PhoneType',$phone);
                $phoneCountryCode = IndexSanityCheckHelper::indexSanityCheck('PhoneCountryCode',$phone);
                $phoneAreaCode = IndexSanityCheckHelper::indexSanityCheck('PhoneAreaCode', $phone);
                $phoneNumberRaw = IndexSanityCheckHelper::indexSanityCheck('PhoneNumber', $phone);
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
            $newContact['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('ContactID', $contact);
            $newContact['display_name'] = IndexSanityCheckHelper::indexSanityCheck('FirstName', $contact);;
            $newContact['last_name'] = IndexSanityCheckHelper::indexSanityCheck('LastName', $contact);
            $newContact['email_address'] =IndexSanityCheckHelper::indexSanityCheck('EmailAddress', $contact);;
            $newContact['website'] = IndexSanityCheckHelper::indexSanityCheck('Website', $contact);;
            $newContact['type'] = (IndexSanityCheckHelper::indexSanityCheck('IsSupplier', $contact) === 'true' ? 'SUPPLIER' : 'CUSTOMER');
            $newContact['is_individual'] = (IndexSanityCheckHelper::indexSanityCheck('IsSupplier', $contact) === 'true' ? true : false);
            $newContact['bank_account_details'] = IndexSanityCheckHelper::indexSanityCheck('BankAccountDetails', $contact);;
            $newContact['tax_number'] = IndexSanityCheckHelper::indexSanityCheck('TaxNumber', $contact);;
            $newContact['accounts_receivable_tax_type'] = IndexSanityCheckHelper::indexSanityCheck('ReceivableTaxType', $contact);;
            $newContact['accounts_payable_tax_type'] = IndexSanityCheckHelper::indexSanityCheck('AccountsPayableTaxType', $contact);;
            $newContact['default_currency'] = IndexSanityCheckHelper::indexSanityCheck('DefaultCurrency', $contact);
            if (IndexSanityCheckHelper::indexSanityCheck('ContactGroups', $contact)) {
                $newContact = $this->parseContactGroups($contact['ContactGroups'], $newContact);
            }
            if (IndexSanityCheckHelper::indexSanityCheck('Phones', $contact)) {
                $newContact = $this->parsePhones($contact['Phones'], $newContact);
            }
            if (IndexSanityCheckHelper::indexSanityCheck('Addresses', $contact)) {
                $newContact = $this->parseAddresses($contact['Addresses'], $newContact);
            }

            array_push($contacts, $newContact);
        }

        return $contacts;
    }
}