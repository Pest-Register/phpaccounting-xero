<?php

namespace PHPAccounting\Xero\Message\ContactGroups\Requests;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\ContactGroups\Responses\CreateContactGroupResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\ContactGroup;


/**
 * Create Contact Group(s)
 * @package PHPAccounting\XERO\Message\ContactGroups\Requests
 */
class CreateContactGroupRequest extends AbstractRequest
{

    /**
     * Set Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @param string $value Contact Name
     * @return CreateContactGroupRequest
     */
    public function setName($value){
        return $this->setParameter('name', $value);
    }

    /**
     * Set Status from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @param $value
     * @return CreateContactGroupRequest
     */
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }

    /**
     * Get Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @return mixed
     */
    public function getName() {
        return $this->getParameter('name');
    }

    /**
     * Get Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @return mixed
     */
    public function getStatus() {
        return $this->getParameter('status');
    }

    /**
     * Set Contacts Array from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @param $value
     * @return CreateContactGroupRequest
     */
    public function setContacts($value) {
        return $this->setParameter('contacts', $value);
    }

    /**
     * Get Contacts Array from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @return mixed
     */
    public function getContacts() {
        return $this->getParameter('contacts');
    }

    /**
     * Get Contact Array with Contact ID References
     * @access public
     * @param array $data Array of Xero Contacts
     * @return array
     */
    private function getContactData($data) {
        $contacts = [];
        foreach($data as $contact) {
            $newContact = new Contact();
            $newContact->setContactID(IndexSanityCheckHelper::indexSanityCheck('accounting_id', $contact));
            array_push($contacts, $newContact);
        }

        return $contacts;
    }

    /**
     * Add Contacts to Contact Group
     * @param ContactGroup $contactGroup Xero Contact Group Object
     * @param array $contacts Array of Contacts (ContactID References)
     */
    private function addContactsToGroup(ContactGroup $contactGroup, $contacts) {
        if ($contacts) {
            foreach($contacts as $contact) {
                $newContact = new Contact();
                $newContact->setContactID($contact['ContactID']);
                $contactGroup->addContact($newContact);
            }
        }
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
        $this->issetParam('Status', 'status');
        $this->data['Contacts'] = ($this->getContacts() != null ? $this->getContactData($this->getContacts()) : null);
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|CreateContactGroupResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();

            $contactGroup = new ContactGroup($xero);
            foreach ($data as $key => $value){
                if ($key !== 'Contacts') {
                    $methodName = 'set'. $key;
                    $contactGroup->$methodName($value);
                }
            }

            $response = $contactGroup->save();

            if ($this->data['Contacts']) {
                $responseData = $response->getElements()[0];
                if (array_key_exists('ContactGroupID', $response->getElements()[0])) {
                    $contactGroup = new ContactGroup($xero);
                    $contactGroup->setContactGroupID($responseData['ContactGroupID']);
                    $this->addContactsToGroup($contactGroup, $this->data['Contacts']);
                    $response = $contactGroup->save();
                }
            }

        } catch (\Exception $exception){
            $response = [
                'status' => 'error',
                'detail' => json_decode(print_r($exception->getResponse()->getBody()->getContents(), true))->detail
            ];
            return $this->createResponse($response);
        }

        return $this->createResponse($response->getElements());
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return CreateContactGroupResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateContactGroupResponse($this, $data);
    }
}