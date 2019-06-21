<?php

namespace PHPAccounting\Xero\Message\Accounts\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

/**
 * Create ContactGroup(s) Response
 * @package PHPAccounting\XERO\Message\ContactGroups\Responses
 */
class CreateAccountResponse extends AbstractResponse
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
     * Add Contacts to Contact Group
     * @param $data Array of Contacts
     * @param array $contactGroup Xero Contact Group Mapped Array
     * @return mixed
     */
    private function parseContacts($data, $contactGroup) {
        $contactGroup['contacts'] = [];
        if ($data) {
            $contacts = [];
            foreach($data as $contact) {
                $newContact = [];
                $newContact['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('ContactID',$contact);
                $newContact['name'] = IndexSanityCheckHelper::indexSanityCheck('Name',$contact);
                array_push($contacts, $newContact);
            }
            $contactGroup['contacts'] = $contacts;
        }

        return $contactGroup;
    }

    /**
     * Fetch Contact Groups from Response
     * @return mixed
     */
    public function getContactGroups(){
        $contactGroups = [];
        foreach ($this->data as $contactGroup) {
            $newContactGroup = [];
            $newContactGroup['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('ContactGroupID', $contactGroup);
            $newContactGroup['name'] = IndexSanityCheckHelper::indexSanityCheck('Name', $contactGroup);
            $newContactGroup['status'] = IndexSanityCheckHelper::indexSanityCheck('Status', $contactGroup);
            if (IndexSanityCheckHelper::indexSanityCheck('Contacts', $contactGroup)) {
                $newContactGroup = $this->parseContacts($contactGroup['Contacts'], $newContactGroup);
            }
            array_push($contactGroups, $newContactGroup);
        }

        return $contactGroups;
    }
}