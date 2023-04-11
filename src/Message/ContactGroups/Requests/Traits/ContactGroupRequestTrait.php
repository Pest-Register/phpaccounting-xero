<?php

namespace PHPAccounting\Xero\Message\ContactGroups\Requests\Traits;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\ContactGroups\Requests\CreateContactGroupRequest;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\ContactGroup;

trait ContactGroupRequestTrait
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
}