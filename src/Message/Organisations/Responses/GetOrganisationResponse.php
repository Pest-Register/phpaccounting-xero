<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 12/07/2019
 * Time: 9:09 AM
 */

namespace PHPAccounting\Xero\Message\Organisations\Responses;


use Calcinai\OAuth2\Client\XeroTenant;
use PHPAccounting\Xero\Message\AbstractXeroResponse;

class GetOrganisationResponse extends AbstractXeroResponse
{

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
            if ($organisation instanceof XeroTenant) {
                $newOrganisation['accounting_id'] = $organisation->tenantId;
            } else {
                $newOrganisation['accounting_id'] = $organisation->getOrganisationID();
                $newOrganisation['name'] = $organisation->getName();
                $newOrganisation['country_code'] = $organisation->getCountryCode();

                $newOrganisation['legal_name'] = $organisation->getLegalName();
                $newOrganisation['gst_registered'] = $organisation->getPaysTax();
                $newOrganisation['version'] = $organisation->getVersion();
                $newOrganisation['organisation_type'] = $organisation->getOrganisationEntityType();
                $newOrganisation['base_currency'] = $organisation->getBaseCurrency();
                $newOrganisation['is_demo_company'] = $organisation->getIsDemoCompany();
                $newOrganisation['organisation_status'] = $organisation->getOrganisationStatus();
                $newOrganisation['tax_number'] = $organisation->getTaxNumber();
                $newOrganisation = $this->parsePhones($organisation->getPhones(), $newOrganisation);
                $newOrganisation = $this->parseAddresses($organisation->getAddresses(), $newOrganisation);
            }

            array_push($organisations, $newOrganisation);
        }
        return $organisations;
    }
}