<?php
/**
 * Created by IntelliJ IDEA.
 * User: Max
 * Date: 5/28/2019
 * Time: 1:36 PM
 */

namespace PHPAccounting\Xero\Message\ContactGroups\Responses;


use Omnipay\Common\Message\AbstractResponse;
use XeroPHP\Models\Accounting\ContactGroup;

class GetContactGroupResponse extends AbstractResponse
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
            $newContactGroup['accounting_id'] = $contactGroup->getContactGroupID();
            $newContactGroup['name'] = $contactGroup->getName();
            array_push($contactGroups, $newContactGroup);
        }

        return $contactGroups;
    }
}