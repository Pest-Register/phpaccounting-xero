<?php

namespace PHPAccounting\Xero\Message\ManualJournals\Responses;


use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

class DeleteManualJournalResponse extends AbstractResponse
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
            if (is_array($this->data)) {
                if (count($this->data) === 0) {
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
     * @return string
     */
    public function getErrorMessage(){
        if ($this->data) {
            if(array_key_exists('status', $this->data)){
                return ErrorResponseHelper::parseErrorResponse($this->data['detail'], 'Manual Journal');
            }
            if (count($this->data) === 0) {
                return 'NULL Returned from API or End of Pagination';
            }
        }
        return null;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getJournals(){
        $journals = [];
        foreach ($this->data as $journal) {
            $newInvoice = [];
            $newInvoice['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('ManualJournalID', $journal);
            $newInvoice['status'] = IndexSanityCheckHelper::indexSanityCheck('Status', $journal);
            $newInvoice['updated_at'] = IndexSanityCheckHelper::indexSanityCheck('UpdatedDateUTC', $journal);
            array_push($invoices, $newInvoice);
        }

        return $journals;
    }
}