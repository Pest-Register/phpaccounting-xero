<?php

namespace PHPAccounting\Xero\Message\Contacts\Responses;
use Omnipay\Common\Message\AbstractResponse;


class CreateContactResponse extends AbstractResponse
{

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->data != null;
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
    private function indexSanityCheck ($key, $array) {
        $value = '';
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }
        return $value;
    }
    /**
     * Return all Contacts with Generic Schema Variable Assignment
     * @return array
     */
    public function getContacts(){
        $contacts = [];
        foreach ($this->data as $contact) {
            $newContact = [];
            $newContact['accounting_id'] = $this->indexSanityCheck('ContactID', $contact);
            $newContact['display_name'] = $this->indexSanityCheck('FirstName', $contact);;
            $newContact['last_name'] = $this->indexSanityCheck('LastName', $contact);
            $newContact['email_address'] =$this->indexSanityCheck('EmailAddress', $contact);;
            $newContact['website'] = $this->indexSanityCheck('Website', $contact);;
            $newContact['type'] = ($this->indexSanityCheck('IsSupplier', $contact) === 'true' ? 'SUPPLIER' : 'CUSTOMER');
            $newContact['is_individual'] = ($this->indexSanityCheck('IsSupplier', $contact) === 'true' ? true : false);
            $newContact['bank_account_details'] = $this->indexSanityCheck('BankAccountDetails', $contact);;
            $newContact['tax_number'] = $this->indexSanityCheck('TaxNumber', $contact);;
            $newContact['accounts_receivable_tax_type'] = $this->indexSanityCheck('ReceivableTaxType', $contact);;
            $newContact['accounts_payable_tax_type'] = $this->indexSanityCheck('AccountsPayableTaxType', $contact);;
            $newContact['default_currency'] = $this->indexSanityCheck('DefaultCurrency', $contact);
//            $newContact = $this->parseContactGroups($contact->getContactGroups(), $newContact);
            $newContact = $this->parsePhones($contact['Phones'], $newContact);
//            $newContact = $this->parseAddresses($contact->getAddresses(), $newContact);
            array_push($contacts, $newContact);
        }

        return $contacts;
    }
}