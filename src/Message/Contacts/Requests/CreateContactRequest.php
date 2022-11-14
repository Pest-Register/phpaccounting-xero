<?php

namespace PHPAccounting\Xero\Message\Contacts\Requests;
use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Contacts\Requests\Traits\ContactRequestTrait;
use PHPAccounting\Xero\Message\Contacts\Responses\CreateContactResponse;
use XeroPHP\Models\Accounting\Address;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\ContactGroup;
use XeroPHP\Models\Accounting\Phone;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use XeroPHP\Remote\Exception;

/**
 * Create Contact(s)
 * @package PHPAccounting\XERO\Message\Contacts\Requests
 */
class CreateContactRequest extends AbstractXeroRequest
{
    use ContactRequestTrait;

    public string $model = 'Contact';


    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {

        try {
            $this->validate('name');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }
        $this->issetParam('Name', 'name');
        $this->issetParam('FirstName', 'first_name');
        $this->issetParam('LastName', 'last_name');
        $this->issetParam('EmailAddress', 'email_address');
        $this->issetParam('Website', 'website');
        $this->issetParam('BankAccountDetails', 'bank_account_details');
        $this->issetParam('TaxNumber', 'tax_number');
        $this->issetParam('AccountsReceivableTaxType', 'accounts_receivable_tax_type_id');
        $this->issetParam('AccountsPayableTaxType', 'accounts_payable_tax_type_id');
        $this->issetParam('DefaultCurrency', 'default_currency');
        $this->issetParam('ContactStatus','status');

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
        if($data instanceof InvalidRequestException) {
            $response = parent::handleRequestException($data, 'InvalidRequestException');
            return $this->createResponse($response);
        }
        try {
            $xero = $this->createXeroApplication();


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

            $response = $xero->save($contact);

        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
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
