<?php
/**
 * Created by IntelliJ IDEA.
 * User: MaxYendall
 * Date: 6/09/2019
 * Time: 3:14 PM
 */

namespace PHPAccounting\Xero\Message\ManualJournals\Responses;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;

class CreateManualJournalResponse extends AbstractXeroResponse
{

    private function parseJournalLines($data, $journal) {
        if ($data) {
            $lineItems = [];
            foreach($data as $lineItem) {
                $newLineItem = [];
                $newLineItem['description'] = IndexSanityCheckHelper::indexSanityCheck('Description', $lineItem);
                $newLineItem['line_amount'] = IndexSanityCheckHelper::indexSanityCheck('LineAmount', $lineItem);
                $newLineItem['tax_amount'] = IndexSanityCheckHelper::indexSanityCheck('TaxAmount', $lineItem);
                $newLineItem['account_code'] = IndexSanityCheckHelper::indexSanityCheck('AccountCode', $lineItem);
                $newLineItem['tax_type_id'] = IndexSanityCheckHelper::indexSanityCheck('TaxType', $lineItem);
                $taxAmount = 0;
                if (is_array($lineItem)) {
                    if (array_key_exists('TaxAmount', $lineItem)) {
                        $taxAmount = $lineItem['TaxAmount'];
                    }
                } elseif (is_object($lineItem)) {
                    if (property_exists($lineItem, 'TaxAmount')) {
                        $taxAmount = $lineItem->TaxAmount;
                    }
                }
                $newJournalItem['net_amount'] = $taxAmount + $lineItem['LineAmount'];
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