<?php


namespace PHPAccounting\Xero\Message\Journals\Responses;


use Omnipay\Common\Message\AbstractResponse;
use XeroPHP\Models\Accounting\Journal;

class GetJournalResponse extends AbstractResponse
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

    private function parseJournalItems($data, $journal) {
        if ($data) {
            $journalItems = [];
            foreach($data as $journalItem) {
                $newJournalItem = [];
                $newJournalItem['accounting_id'] = $journalItem->getJournalLineID();
                $newJournalItem['account_id'] = $journalItem->getAccountID();
                $newJournalItem['account_code'] = $journalItem->getAccountCode();
                $newJournalItem['account_type'] = $journalItem->getAccountType();
                $newJournalItem['account_name'] = $journalItem->getAccountName();
                $newJournalItem['description'] = $journalItem->getDescription();
                $newJournalItem['net_amount'] = $journalItem->getNetAmount();
                $newJournalItem['gross_amount'] = $journalItem->getGrossAmount();
                $newJournalItem['tax_amount'] = $journalItem->getTaxAmount();
                $newJournalItem['tax_type'] = $journalItem->getTaxType();
                $newJournalItem['tax_name'] = $journalItem->getTaxName();
                array_push($journalItems, $newJournalItem);
            }

            $journal['journal_data'] = $journalItems;
        }

        return $journal;
    }
    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getJournals(){
        $journals = [];
        if ($this->data instanceof Journal){
            $journal = $this->data;
            $newJournal = [];
            $newJournal['accounting_id'] = $journal->getJournalID();
            $newJournal['date'] = $journal->getJournalDate();
            $newJournal['reference_number'] = $journal->getJournalNumber();
            $newJournal['reference_id'] = $journal->getReference();
            $newJournal['source_id'] = $journal->getSourceID();
            $newJournal['source_type'] = $journal->getSourceType();
            $newJournal['updated_at'] = $journal->getCreatedDateUTC();

            $newJournal = $this->parseJournalItems($journal->getJournalLines(), $newJournal);

            array_push($journals, $newJournal);

        } else {
            foreach ($this->data as $journal) {
                $newJournal = [];
                $newJournal['accounting_id'] = $journal->getJournalID();
                $newJournal['date'] = $journal->getJournalDate();
                $newJournal['reference_number'] = $journal->getJournalNumber();
                $newJournal['reference_id'] = $journal->getReference();
                $newJournal['source_id'] = $journal->getSourceID();
                $newJournal['source_type'] = $journal->getSourceType();
                $newJournal['updated_at'] = $journal->getCreatedDateUTC();

                $newJournal = $this->parseJournalItems($journal->getJournalLines(), $newJournal);

                array_push($journals, $newJournal);
            }
        }


        return $journals;
    }
}