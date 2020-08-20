<?php
namespace PHPAccounting\Xero\Message\ManualJournals\Responses;


use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

class UpdateManualJournalResponse extends AbstractResponse
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
                    $this->data['detail'],
                    $this->data['type'],
                    $this->data['status'],
                    $this->data['error_code'],
                    $this->data['status_code'],
                    $this->data['detail'],
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

    /**
     * @param $data
     * @param $journal
     * @return mixed
     */
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