<?php


namespace PHPAccounting\Xero\Message\Journals\Responses;

use PHPAccounting\Xero\Message\AbstractXeroResponse;
use XeroPHP\Models\Accounting\Journal;

class GetJournalResponse extends AbstractXeroResponse
{

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
                $newJournalItem['tax_type_id'] = $journalItem->getTaxType();

                if ((float) $journalItem->getGrossAmount() < 0 ) {
                    $newJournalItem['is_credit'] = false;
                } else {
                    $newJournalItem['is_credit'] = true;
                }
                array_push($journalItems, $newJournalItem);
            }

            $journal['journal_data'] = $journalItems;
        }
        return $journal;
    }

    private function parseData($journal) {
        $newJournal = [];
        $newJournal['accounting_id'] = $journal->getJournalID();
        $newJournal['date'] = $journal->getJournalDate();
        $newJournal['reference_number'] = $journal->getJournalNumber();
        $newJournal['reference_id'] = $journal->getReference();
        $newJournal['source_id'] = $journal->getSourceID();
        $newJournal['source_type'] = $journal->getSourceType();
        $newJournal = $this->parseJournalItems($journal->getJournalLines(), $newJournal);

        return $newJournal;
    }
    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getJournals(){
        $journals = [];
        if ($this->data instanceof Journal){
            $newJournal = $this->parseData($this->data);
            array_push($journals, $newJournal);

        } else {
            foreach ($this->data as $journal) {
                $newJournal = $this->parseData($journal);
                array_push($journals, $newJournal);
            }
        }

        return $journals;
    }
}