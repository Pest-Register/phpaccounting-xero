<?php

namespace PHPAccounting\Xero\Message\ManualJournals\Responses;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;

class DeleteManualJournalResponse extends AbstractXeroResponse
{

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