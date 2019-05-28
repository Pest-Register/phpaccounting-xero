<?php

namespace PHPAccounting\XERO\Message\ContactGroups\Responses;


use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

class CreateContactGroupResponse extends AbstractResponse
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
    private function parseContacts($data, $contactGroup) {
        $contactGroup['contacts'] = [];
        if ($data) {
            $contacts = [];
            foreach($data as $contact) {
                $newContact = [];
                $newContact['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('ContactID',$contact);
                array_push($contacts, $newContact);
            }
            $contact['contacts'] = $contacts;
        }

        return $contactGroup;
    }

    /**
     * Return all Contacts with Generic Schema Variable Assignment
     * @return array
     */
    public function getContactGroups(){
        $contactGroups = [];
        foreach ($this->data as $contactGroup) {
            $newContactGroup = [];
            $newContactGroup['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('ContactGroupID', $contactGroup);
            $newContactGroup['name'] = IndexSanityCheckHelper::indexSanityCheck('Name', $contactGroup);
            $newContactGroup['status'] = IndexSanityCheckHelper::indexSanityCheck('Status', $contactGroup);
            if (IndexSanityCheckHelper::indexSanityCheck('Contacts', $contactGroups)) {
                $newContactGroup = $this->parseContacts($contactGroup['Contacts'], $newContactGroup);
            }
            array_push($contactGroups, $newContactGroup);
        }

        return $contactGroups;
    }
}