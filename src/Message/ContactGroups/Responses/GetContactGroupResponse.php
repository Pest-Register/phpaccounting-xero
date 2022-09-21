<?php

namespace PHPAccounting\Xero\Message\ContactGroups\Responses;

use PHPAccounting\Xero\Message\AbstractXeroResponse;
use XeroPHP\Models\Accounting\ContactGroup;

/**
 * Get ContactGroup(s) Response
 * @package PHPAccounting\XERO\Message\ContactGroups\Responses
 */
class GetContactGroupResponse extends AbstractXeroResponse
{

    private function parseData($contactgroup): array
    {
        $newContactGroup = [];
        $newContactGroup['accounting_id'] = $contactgroup->getContactGroupID();
        $newContactGroup['name'] = $contactgroup->getName();
        $newContactGroup['status'] = $contactgroup->getStatus();
        return $newContactGroup;
    }

    /**
     * Return all Contact Groups with Generic Schema Variable Assignment
     * @return array
     */
    public function getContactGroups(){
        $contactGroups = [];
        if ($this->data instanceof ContactGroup){
            $newContactGroup = $this->parseData($this->data);
            array_push($contactGroups, $newContactGroup);
        }
        else {
            foreach ($this->data as $contactGroup) {
                $newContactGroup = $this->parseData($contactGroup);
                array_push($contactGroups, $newContactGroup);
            }
        }

        return $contactGroups;
    }
}