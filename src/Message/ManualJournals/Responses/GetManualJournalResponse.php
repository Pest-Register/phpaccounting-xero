<?php


namespace PHPAccounting\Xero\Message\ManualJournals\Responses;

use PHPAccounting\Xero\Message\AbstractXeroResponse;
use XeroPHP\Models\Accounting\ManualJournal;

class GetManualJournalResponse extends AbstractXeroResponse
{

    private function parseJournalItems($data, $journal) {
        if ($data) {
            $journalItems = [];
            foreach($data as $journalItem) {
                $newJournalItem = [];
                $newJournalItem['account_code'] = $journalItem->getAccountCode();
                $newJournalItem['description'] = $journalItem->getDescription();
                $newJournalItem['gross_amount'] = $journalItem->getLineAmount();
                $newJournalItem['tax_amount'] = $journalItem->getTaxAmount();
                $newJournalItem['tax_type_id'] = $journalItem->getTaxType();
                $newJournalItem['is_credit'] = ($journalItem->getLineAmount() < 0 ? false : true);
                if ($journalItem->getTaxAmount()) {
                    $newJournalItem['net_amount'] = $journalItem->getTaxAmount() + $journalItem->getLineAmount();
                } else {
                    $newJournalItem['net_amount'] = $journalItem->getLineAmount();
                }
                array_push($journalItems, $newJournalItem);
            }

            $journal['journal_data'] = $journalItems;
        }
        return $journal;
    }

    private function parseData($journal) {
        $newJournal = [];
        $newJournal['accounting_id'] = $journal->getManualJournalID();
        $newJournal['date'] = $journal->getDate();
        $newJournal['narration'] = $journal->getNarration();
        $newJournal = $this->parseJournalItems($journal->getJournalLines(), $newJournal);

        return $newJournal;
    }
    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getManualJournals(){
        $journals = [];
        if ($this->data instanceof ManualJournal){
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