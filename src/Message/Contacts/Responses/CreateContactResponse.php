<?php

namespace PHPAccounting\Xero\Message\Contacts\Responses;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;

/**
 * Create Contact(s) Response
 * @package PHPAccounting\XERO\Message\Contacts\Responses
 */
class CreateContactResponse extends AbstractXeroResponse
{

    /**
     * Add ContactGroups to Contact
     * @param $data Array of ContactGroups
     * @param array $contact Xero Contact Object Mapping
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

    private function convertAddressType($data) {
        if ($data) {
            switch ($data) {
                case 'STREET':
                    return 'PRIMARY';
                case 'POBOX':
                    return 'STRUCTURE';
            }
        }
        return $data;
    }

    /**
     * Add Addresses to Contact
     * @param $data Array of Addresses
     * @param array $contact Xero Contact Object Mapping
     * @return mixed
     */
    private function parseAddresses($data, $contact) {
        $contact['addresses'] = [];
        if ($data) {
            $addresses = [];
            foreach($data as $address) {
                $newAddress = [];
                $addressType = '';
                if (is_array($address)){
                    if (array_key_exists('AddressType', $address)) {
                        $addressType = $address['AddressType'];
                    }
                }
                else if (is_object($address)) {
                    if (property_exists($address, 'AddressType')) {}
                    $addressType = $address->AddressType;
                }
                $newAddress['address_type'] = $this->convertAddressType($addressType);
                $newAddress['address_line_1'] = IndexSanityCheckHelper::indexSanityCheck('AddressLine1',$address);;
                $newAddress['city'] = IndexSanityCheckHelper::indexSanityCheck('City',$address);
                $newAddress['postal_code'] = IndexSanityCheckHelper::indexSanityCheck('PostalCode',$address);
                $newAddress['state'] = IndexSanityCheckHelper::indexSanityCheck('Region', $address);
                $newAddress['country'] = IndexSanityCheckHelper::indexSanityCheck('Country',$address);
                array_push($addresses, $newAddress);
            }
            $contact['addresses'] = $addresses;
        }

        return $contact;
    }

    /**
     * Add Phones to Contact
     * @param $data Array of Phones
     * @param array $contact Xero Contact Object Mapping
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
     * @param $isSupplier
     * @param $isCustomer
     * @param $contact
     * @return mixed
     */
    private function parseTypes($isSupplier, $isCustomer, $contact) {
        $contact['types'] = [];
        if ($isSupplier) {
            array_push($contact['types'], 'SUPPLIER');
        }
        if ($isCustomer) {
            array_push($contact['types'], 'CUSTOMER');
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
            $newContact['is_individual'] = (IndexSanityCheckHelper::indexSanityCheck('IsSupplier', $contact) === 'true' ? true : false);
            $newContact['bank_account_details'] = IndexSanityCheckHelper::indexSanityCheck('BankAccountDetails', $contact);;
            $newContact['tax_number'] = IndexSanityCheckHelper::indexSanityCheck('TaxNumber', $contact);;
            $newContact['accounts_receivable_tax_type_id'] = IndexSanityCheckHelper::indexSanityCheck('ReceivableTaxType', $contact);;
            $newContact['accounts_payable_tax_type_id'] = IndexSanityCheckHelper::indexSanityCheck('AccountsPayableTaxType', $contact);;
            $newContact['default_currency'] = IndexSanityCheckHelper::indexSanityCheck('DefaultCurrency', $contact);
            $newContact['updated_at'] = IndexSanityCheckHelper::indexSanityCheck('UpdatedDateUTC', $contact);
            if (IndexSanityCheckHelper::indexSanityCheck('ContactGroups', $contact)) {
                $newContact = $this->parseContactGroups($contact['ContactGroups'], $newContact);
            }
            if (IndexSanityCheckHelper::indexSanityCheck('Phones', $contact)) {
                $newContact = $this->parsePhones($contact['Phones'], $newContact);
            }
            if (IndexSanityCheckHelper::indexSanityCheck('Addresses', $contact)) {
                $newContact = $this->parseAddresses($contact['Addresses'], $newContact);
            }

            if (IndexSanityCheckHelper::indexSanityCheck('IsSupplier', $contact) && IndexSanityCheckHelper::indexSanityCheck('IsCustomer', $contact)) {
                $newContact = $this->parseTypes($contact['IsSupplier'], $contact['IsCustomer'], $newContact);
            }

            array_push($contacts, $newContact);
        }

        return $contacts;
    }
}