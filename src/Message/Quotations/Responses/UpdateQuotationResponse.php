<?php


namespace PHPAccounting\Xero\Message\Quotations\Responses;


use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

class UpdateQuotationResponse extends AbstractResponse
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
                    'Invoice');
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
     * Add LineItems to Quote
     * @param $data Array of LineItems
     * @param array $invoice Xero Quote Object Mapping
     * @return mixed
     */
    private function parseLineItems($data, $invoice) {
        if ($data) {
            $lineItems = [];
            foreach($data as $lineItem) {
                $newLineItem = [];
                $newLineItem['description'] = IndexSanityCheckHelper::indexSanityCheck('Description', $lineItem);
                $newLineItem['unit_amount'] = IndexSanityCheckHelper::indexSanityCheck('UnitAmount', $lineItem);
                $newLineItem['line_amount'] = IndexSanityCheckHelper::indexSanityCheck('LineAmount', $lineItem);
                $newLineItem['quantity'] = IndexSanityCheckHelper::indexSanityCheck('Quantity', $lineItem);
                $newLineItem['discount_rate'] = IndexSanityCheckHelper::indexSanityCheck('DiscountRate', $lineItem);
                $newLineItem['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('LineItemID', $lineItem);
                $newLineItem['discount_amount'] = IndexSanityCheckHelper::indexSanityCheck('DiscountAmount', $lineItem);
                $newLineItem['amount'] = IndexSanityCheckHelper::indexSanityCheck('LineAmount', $lineItem);
                $newLineItem['code'] = IndexSanityCheckHelper::indexSanityCheck('AccountCode', $lineItem);
                array_push($lineItems, $newLineItem);
            }

            $invoice['quotation_data'] = $lineItems;
        }

        return $invoice;
    }

    /**
     * Add Contact to Quote
     * @param $data Array of single Contact
     * @param array $invoice Xero Quote Object Mapping
     * @return mixed
     */
    private function parseContact($data, $quote) {
        if ($data) {
            $newContact = [];
            $newContact['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('ContactID',$data);
            $newContact['name'] = IndexSanityCheckHelper::indexSanityCheck('Name',$data);
            $quote['contact'] = $newContact;
        }

        return $quote;
    }

    /**
     * Parse tax calculation method
     * @param $data
     * @return string
     */
    private function parseTaxCalculation($data)  {
        if ($data) {
            switch($data) {
                case 'Exclusive':
                    return 'EXCLUSIVE';
                case 'Inclusive':
                    return 'INCLUSIVE';
                case 'NoTax':
                case 'NOTAX':
                    return 'NONE';
                case 'EXCLUSIVE':
                case 'INCLUSIVE':
                    return $data;
            }
        }
        return 'NONE';
    }

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
            $newQuote['sub_total'] = IndexSanityCheckHelper::indexSanityCheck('SubTotal', $quote);
            $newQuote['total_tax'] = IndexSanityCheckHelper::indexSanityCheck('TotalTax', $quote);
            $newQuote['total'] = IndexSanityCheckHelper::indexSanityCheck('Total', $quote);
            $newQuote['currency'] = IndexSanityCheckHelper::indexSanityCheck('CurrencyCode', $quote);
            $newQuote['quotation_number'] = IndexSanityCheckHelper::indexSanityCheck('QuoteNumber', $quote);
            $newQuote['currency_rate'] = IndexSanityCheckHelper::indexSanityCheck('CurrencyRate', $quote);
            $newQuote['discount_total'] = IndexSanityCheckHelper::indexSanityCheck('TotalDiscount', $quote);
            $newQuote['date'] = IndexSanityCheckHelper::indexSanityCheck('Date', $quote);
            $newQuote['expiry_date'] = IndexSanityCheckHelper::indexSanityCheck('ExpiryDate', $quote);
            $newQuote['updated_at'] = IndexSanityCheckHelper::indexSanityCheck('UpdatedDateUTC', $quote);
            $newQuote['gst_inclusive'] = $this->parseTaxCalculation(IndexSanityCheckHelper::indexSanityCheck('LineAmountTypes', $quote));
            $newQuote['title'] = IndexSanityCheckHelper::indexSanityCheck('Title', $quote);
            $newQuote['terms'] = IndexSanityCheckHelper::indexSanityCheck('Terms', $quote);
            $newQuote['summary'] = IndexSanityCheckHelper::indexSanityCheck('Summary', $quote);

            if (IndexSanityCheckHelper::indexSanityCheck('Contact', $quote)) {
                $newQuote = $this->parseContact($quote['Contact'], $newQuote);
            }
            if (IndexSanityCheckHelper::indexSanityCheck('LineItems', $quote)) {
                $newQuote = $this->parseLineItems($quote['LineItems'], $newQuote);
            }

            array_push($quotes, $newQuote);
        }

        return $quotes;
    }
}