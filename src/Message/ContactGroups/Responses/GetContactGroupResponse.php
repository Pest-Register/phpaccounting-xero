<?php

namespace PHPAccounting\Xero\Message\ContactGroups\Responses;

use Omnipay\Common\Message\AbstractResponse;

/**
 * Get ContactGroup(s) Response
 * @package PHPAccounting\XERO\Message\ContactGroups\Responses
 */
class GetContactGroupResponse extends AbstractResponse
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
     * Return all Contact Groups with Generic Schema Variable Assignment
     * @return array
     */
    public function getContactGroups(){
        $contactGroups = [];
        foreach ($this->data as $contactGroup) {
            $newContactGroup = [];
            $newContactGroup['accounting_id'] = $contactGroup->getContactGroupID();
            $newContactGroup['name'] = $contactGroup->getName();
            $newContactGroup['status'] = $contactGroup->getStatus();
            array_push($contactGroups, $newContactGroup);
        }

        return $contactGroups;
    }
}