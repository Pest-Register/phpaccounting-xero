<?php

namespace PHPAccounting\Xero\Message\Contacts\Responses;
use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\XERO\Helpers\IndexSanityCheckHelper;

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
                $newAddress['address_type'] =  $address->getAddressType();
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
        if ($data) {
            var_dump($data);
//            foreach($data as $phone) {
//                $phoneNumber = $phone->getPhoneCountryCode().$phone->getPhoneAreaCode().$phone->getPhoneNumber();
//                switch($phone->getPhoneType()){
//                    case 'DEFAULT':
//                        $contact['business_hours_phone'] = $phoneNumber;
//                        break;
//                    case 'DDI':
//                        $contact['after_hours_phone'] = $phoneNumber;
//                        break;
//                    case 'MOBILE':
//                        $contact['mobile_phone'] = $phoneNumber;
//                        break;
//                }
//            }
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
//            $newContact = $this->parseContactGroups($contact->getContactGroups(), $newContact);
            $newContact = $this->parsePhones($contact['Phones'], $newContact);
//            $newContact = $this->parseAddresses($contact->getAddresses(), $newContact);
            array_push($contacts, $newContact);
        }

        return $contacts;
    }
}