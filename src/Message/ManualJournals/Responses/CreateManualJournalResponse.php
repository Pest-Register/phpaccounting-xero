<?php
/**
 * Created by IntelliJ IDEA.
 * User: MaxYendall
 * Date: 6/09/2019
 * Time: 3:14 PM
 */

namespace PHPAccounting\Xero\Message\ManualJournals\Responses;


use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

class CreateManualJournalResponse extends AbstractResponse
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

    private function parseJournalLines($data, $journal) {
        if ($data) {
            $lineItems = [];
            foreach($data as $lineItem) {
                $newLineItem = [];
                $newLineItem['description'] = IndexSanityCheckHelper::indexSanityCheck('Description', $lineItem);
                $newLineItem['line_amount'] = IndexSanityCheckHelper::indexSanityCheck('LineAmount', $lineItem);
                $newLineItem['tax_amount'] = IndexSanityCheckHelper::indexSanityCheck('TaxAmount', $lineItem);
                $newLineItem['account_code'] = IndexSanityCheckHelper::indexSanityCheck('AccountCode', $lineItem);
                $newLineItem['tax_type'] = IndexSanityCheckHelper::indexSanityCheck('TaxType', $lineItem);
                if (array_key_exists('TaxAmount',$lineItem)) {
                    $newJournalItem['net_amount'] = $lineItem['TaxAmount'] + $lineItem['LineAmount'];
                } else {
                    $newJournalItem['net_amount'] = $lineItem['LineAmount'];
                }
                array_push($lineItems, $newLineItem);
            }

            $journal['journal_data'] = $lineItems;
        }

        return $journal;
    }
    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getManualJournals(){
        $journals = [];
        foreach ($this->data as $journal) {
            $newJournal = [];
            $newJournal['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('ManualJournalID', $journal);
            $newJournal['status'] = IndexSanityCheckHelper::indexSanityCheck('Status', $journal);
            $newJournal['narration'] = IndexSanityCheckHelper::indexSanityCheck('Narration', $journal);
            $newJournal['updated_at'] = IndexSanityCheckHelper::indexSanityCheck('UpdatedDateUTC', $journal);

            if (IndexSanityCheckHelper::indexSanityCheck('JournalLines', $journal)) {
                $newJournal = $this->parseJournalLines($journal['JournalLines'], $newJournal);
            }

            array_push($journals, $newJournal);
        }

        return $journals;
    }
}