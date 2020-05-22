<?php

namespace PHPAccounting\Xero\Message\ContactGroups\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

/**
 * Create ContactGroup(s) Response
 * @package PHPAccounting\XERO\Message\ContactGroups\Responses
 */
class CreateContactGroupResponse extends AbstractResponse
{
    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if ($this->data) {
            if(array_key_exists('status', $this->data)){
                return !$this->data['status'] == 'error';
            }
            if ($this->data instanceof \XeroPHP\Remote\Collection) {
                if (count($this->data) == 0) {
                    return false;
                }
            } elseif (is_array($this->data)) {
                if (count($this->data) == 0) {
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return array
     */
    public function getErrorMessage(){
        if ($this->data) {
            if(array_key_exists('status', $this->data)){
                return ErrorResponseHelper::parseErrorResponse($this->data['detail'],$this->data['type'],$this->data, 'Contact Group');
            }
            if (count($this->data) === 0) {
                return ['message' => 'NULL Returned from API or End of Pagination'];
            }
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