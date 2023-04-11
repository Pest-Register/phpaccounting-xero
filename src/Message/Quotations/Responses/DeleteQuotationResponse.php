<?php


namespace PHPAccounting\Xero\Message\Quotations\Responses;


use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;

class DeleteQuotationResponse extends AbstractXeroResponse
{

    /**
     * Parse status
     * @param $data
     * @return string|null
     */
    private function parseStatus($data) {
        if ($data) {
            switch($data) {
                case 'DRAFT':
                case 'DELETED':
                case 'SENT':
                case 'DECLINED':
                case 'ACCEPTED':
                    return $data;
                case 'INVOICED':
                    return 'ACCEPTED';
            }
        }
        return null;
    }

    /**
     * Return all Quotes with Generic Schema Variable Assignment
     * @return array
     */
    public function getQuotations(){
        $quotes = [];
        foreach ($this->data as $quote) {
            $newQuote = [];
            $newQuote['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('QuoteID', $quote);
            $newQuote['status'] = $this->parseStatus(IndexSanityCheckHelper::indexSanityCheck('Status', $quote));
            $newQuote['updated_at'] = IndexSanityCheckHelper::indexSanityCheck('UpdatedDateUTC', $quote);
            array_push($quotes, $newQuote);
        }

        return $quotes;
    }
}