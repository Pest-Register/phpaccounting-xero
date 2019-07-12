<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 12/07/2019
 * Time: 9:09 AM
 */

namespace PHPAccounting\Xero\Message\Organisations\Responses;


use Omnipay\Common\Message\AbstractResponse;

class GetOrganisationResponse extends AbstractResponse
{

    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if(array_key_exists('status', $this->data)){
            return !$this->data['status'] == 'error';
        }
        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return string
     */
    public function getErrorMessage(){
        if(array_key_exists('status', $this->data)){
            return $this->data['detail'];
        }
        return null;
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

    public function getOrganisations(){
        $organisations = [];
        foreach ($this->data as $organisation) {
            $newOrganisation = [];

            //parity with myob
            $newOrganisation['accounting_id'] = $organisation->getOrganisationID();
            $newOrganisation['name'] = $organisation->getName();
            $newOrganisation['country_code'] = $organisation->getCountryCode();

            $newOrganisation['legal_name'] = $organisation->getLegalName();
            $newOrganisation['pays_tax'] = $organisation->getPaysTax();
            $newOrganisation['version'] = $organisation->getVersion();
            $newOrganisation['organisation_type'] = $organisation->getOrganisationEntityType();
            $newOrganisation['base_currency'] = $organisation->getBaseCurrency();
            $newOrganisation['is_demo_company'] = $organisation->getIsDemoCompany();
            $newOrganisation['organisation_status'] = $organisation->getOrganisationStatus();
            $newOrganisation['tax_number'] = $organisation->getTaxNumber();
            $newOrganisation = $this->parsePhones($organisation->getPhones(), $newOrganisation);
            $newOrganisation = $this->parseAddresses($organisation->getAddresses(), $newOrganisation);
            array_push($organisations, $newOrganisation);
        }
        return $organisations;
    }
}