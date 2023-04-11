<?php

namespace PHPAccounting\Xero\Message\ContactGroups\Responses;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;

/**
 * Delete ContactGroup(s) Response
 * @package PHPAccounting\XERO\Message\ContactGroups\Responses
 */
class DeleteContactGroupResponse extends AbstractXeroResponse
{
    /**
     * Return all Contact Groups with Generic Schema Variable Assignment
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