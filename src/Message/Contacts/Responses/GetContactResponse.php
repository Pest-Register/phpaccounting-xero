<?php
namespace PHPAccounting\Xero\Message\Contacts\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Remote\Collection;

/**
 * Get Contact(s) Response
 * @package PHPAccounting\XERO\Message\Contacts\Responses
 */
class GetContactResponse extends AbstractXeroResponse
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
                $newContactGroup['accounting_id'] = $contactGroup->getContactGroupID();
                $newContactGroup['name'] = $contactGroup->getName();
                $newContactGroup['status'] = $contactGroup->getStatus();
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
                $newAddress['address_type'] = $this->convertAddressType($address->getAddressType());
                $newAddress['address_line_1'] = $address->getAddressLine1();
                $newAddress['city'] = $address->getCity();
                $newAddress['state'] = $address->getRegion();
                $newAddress['postal_code'] = $address->getPostalCode();
                $newAddress['country'] = $address->getCountry();
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

    private function parseData($contact) {
        $newContact = [];
        $newContact['accounting_id'] = $contact->getContactID();
        $newContact['display_name'] = $contact->getName();
        $newContact['first_name'] = $contact->getFirstName();
        $newContact['last_name'] = $contact->getLastName();
        $newContact['email_address'] = $contact->getEmailAddress();
        $newContact['website'] = $contact->getWebsite();
        $newContact['is_individual'] = !$contact->getIsSupplier();
        $newContact['bank_account_details'] = $contact->getBankAccountDetails();
        $newContact['tax_number'] = $contact->getTaxNumber();
        $newContact['accounts_receivable_tax_type_id'] = $contact->getAccountsReceivableTaxType();
        $newContact['accounts_payable_tax_type_id'] = $contact->getAccountsPayableTaxType();
        $newContact['default_currency'] = $contact->getDefaultCurrency();
        $newContact['updated_at'] = $contact->getUpdatedDateUTC();
        $newContact['status'] = $contact->getContactStatus();
        $newContact = $this->parseContactGroups($contact->getContactGroups(), $newContact);
        $newContact = $this->parsePhones($contact->getPhones(), $newContact);
        $newContact = $this->parseAddresses($contact->getAddresses(), $newContact);
        $newContact = $this->parseTypes($contact->getIsSupplier(), $contact->getIsCustomer(), $newContact);
        return $newContact;
    }

    /**
     * Return all Contacts with Generic Schema Variable Assignment
     * @return array
     */
    public function getContacts(){
        $contacts = [];
        if ($this->data instanceof Contact) {
            $newContact = $this->parseData($this->data);
            array_push($contacts, $newContact);
        } else {
            foreach ($this->data as $contact) {
                $newContact = $this->parseData($contact);
                array_push($contacts, $newContact);
            }
        }

        return $contacts;
    }
}
