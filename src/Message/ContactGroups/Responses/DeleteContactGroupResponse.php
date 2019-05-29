<?php
/**
 * Created by IntelliJ IDEA.
 * User: Max
 * Date: 5/29/2019
 * Time: 12:31 PM
 */

namespace PHPAccounting\XERO\Message\ContactGroups\Responses;


use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

class DeleteContactGroupResponse extends AbstractResponse
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
            array_push($contactGroups, $newContactGroup);
        }

        return $contactGroups;
    }
}