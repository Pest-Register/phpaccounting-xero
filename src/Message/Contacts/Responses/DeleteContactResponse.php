<?php

namespace PHPAccounting\Xero\Message\Contacts\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

/**
 * Delete Contact(s) Response
 * @package PHPAccounting\XERO\Message\Contacts\Responses
 */
class DeleteContactResponse extends AbstractResponse
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
            if (count($this->data) === 0) {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return string
     */
    public function getErrorMessage(){
        if ($this->data) {
            if(array_key_exists('status', $this->data)){
                return $this->data['detail'];
            }
            if (count($this->data) === 0) {
                return 'NULL Returned from API or End of Pagination';
            }
        }
        return null;
    }

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