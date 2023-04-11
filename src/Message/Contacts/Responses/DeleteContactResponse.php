<?php

namespace PHPAccounting\Xero\Message\Contacts\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;

/**
 * Delete Contact(s) Response
 * @package PHPAccounting\XERO\Message\Contacts\Responses
 */
class DeleteContactResponse extends AbstractXeroResponse
{

    /**
     * Return all Contacts with Generic Schema Variable Assignment
     * @return array
     */
    public function getContacts(){
        $contacts = [];
        foreach ($this->data as $contact) {
            $newContact = [];
            $newContact['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('ContactID', $contact);
            $newContact['name'] = IndexSanityCheckHelper::indexSanityCheck('Name', $contact);
            $newContact['status'] = IndexSanityCheckHelper::indexSanityCheck('ContactStatus', $contact);
            $newContact['updated_at'] = IndexSanityCheckHelper::indexSanityCheck('UpdatedDateUTC', $contact);
            array_push($contacts, $newContact);
        }

        return $contacts;
    }
}
