<?php


namespace PHPAccounting\Xero\Message\ManualJournals\Responses;


use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use XeroPHP\Models\Accounting\Journal;
use XeroPHP\Models\Accounting\ManualJournal;
use XeroPHP\Models\Accounting\TaxRate;

class GetManualJournalResponse extends AbstractResponse
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
            if ($this->data instanceof \XeroPHP\Remote\Collection) {
                if (count($this->data) == 0) {
                    return false;
                }
            } elseif (is_array($this->data)) {
                if (count($this->data) == 0) {
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
     * @return array
     */
    public function getErrorMessage(){
        if ($this->data) {
            if(array_key_exists('status', $this->data)){
                return ErrorResponseHelper::parseErrorResponse(
                    isset($this->data['detail']) ? $this->data['detail'] : null,
                    isset($this->data['type']) ? $this->data['type'] : null,
                    isset($this->data['status']) ? $this->data['status'] : null,
                    isset($this->data['error_code']) ? $this->data['error_code'] : null,
                    isset($this->data['status_code']) ? $this->data['status_code'] : null,
                    isset($this->data['detail']) ? $this->data['detail'] : null,
                    $this->data,
                    'Manual Journal');
            }
            if (count($this->data) === 0) {
                return [
                    'message' => 'NULL Returned from API or End of Pagination',
                    'exception' => 'NULL Returned from API or End of Pagination',
                    'error_code' => null,
                    'status_code' => null,
                    'detail' => null
                ];
            }
        }
        return null;
    }

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
    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getManualJournals(){
        $journals = [];
        if ($this->data instanceof ManualJournal){
            $journal = $this->data;
            $newJournal = [];
            $newJournal['accounting_id'] = $journal->getManualJournalID();
            $newJournal['date'] = $journal->getDate();
            $newJournal['narration'] = $journal->getNarration();
            $newJournal = $this->parseJournalItems($journal->getJournalLines(), $newJournal);

            array_push($journals, $newJournal);

        } else {
            foreach ($this->data as $journal) {
                $newJournal = [];
                $newJournal['accounting_id'] = $journal->getManualJournalID();
                $newJournal['date'] = $journal->getDate();
                $newJournal['narration'] = $journal->getNarration();
                $newJournal = $this->parseJournalItems($journal->getJournalLines(), $newJournal);
                array_push($journals, $newJournal);
            }
        }

        return $journals;
    }
}