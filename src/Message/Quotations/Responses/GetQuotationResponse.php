<?php


namespace PHPAccounting\Xero\Message\Quotations\Responses;


use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;
use XeroPHP\Models\Accounting\Quote;

class GetQuotationResponse extends AbstractXeroResponse
{

    /**
     * Add LineItems to Quote
     * @param $data Array of LineItems
     * @param array $quote Xero Quote Object Mapping
     * @return mixed
     */
    private function parseLineItems($data, $quote) {
        if ($data) {
            $lineItems = [];
            foreach($data as $lineItem) {
                $newLineItem = [];
                $newLineItem['description'] = $lineItem->getDescription();
                $newLineItem['unit_amount'] = $lineItem->getUnitAmount();
                $newLineItem['line_amount'] = $lineItem->getLineAmount();
                $newLineItem['quantity'] = floatval($lineItem->getQuantity());
                $newLineItem['discount_rate'] = $lineItem->getDiscountRate();
                $newLineItem['accounting_id'] = $lineItem->getLineItemID();
                $newLineItem['amount'] = $lineItem->getLineAmount();
                $newLineItem['account_code'] = $lineItem->getAccountCode();
                $newLineItem['item_code'] = $lineItem->getItemCode();
                $newLineItem['tax_amount'] = $lineItem->getTaxAmount();
                $newLineItem['tax_type_id'] = $lineItem->getTaxType();
                $newLineItem['code'] = $lineItem->getAccountCode();
                array_push($lineItems, $newLineItem);
            }

            $quote['quotation_data'] = $lineItems;
        }

        return $quote;
    }

    /**
     * Add Contact to Quote
     * @param $data Array of single Contact
     * @param array $invoice Xero Quote Object Mapping
     * @return mixed
     */
    private function parseContact($data, $invoice) {
        if ($data) {
            $newContact = [];
            $newContact['accounting_id'] = $data->getContactID();
            $newContact['name'] = $data->getName();
            $invoice['contact'] = $newContact;
        }

        return $invoice;
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
                case 'ACCEPTED':
                    return $data;
                case 'DECLINED':
                    return 'REJECTED';
                case 'INVOICED':
                    return 'ACCEPTED';
            }
        }
        return null;
    }

    private function parseData($quote) {
        $newQuote = [];
        $newQuote['accounting_id'] = $quote->getQuoteID();
        $newQuote['status'] = $this->parseStatus($quote->getStatus());
        $newQuote['sub_total'] = $quote->getSubTotal();
        $newQuote['total_tax'] = $quote->getTotalTax();
        $newQuote['total'] = $quote->getTotal();
        $newQuote['currency'] = $quote->getCurrencyCode();
        $newQuote['quotation_number'] = $quote->getQuoteNumber();
        $newQuote['currency_rate'] = $quote->getCurrencyRate();
        $newQuote['date'] = $quote->getDate();
        $newQuote['expiry_date'] = $quote->getExpiryDate();
        $newQuote['gst_inclusive'] = $this->parseTaxCalculation($quote->getLineAmountTypes());
        $newQuote['updated_at'] = $quote->getUpdatedDateUTC();
        $newQuote['title'] = $quote->getTitle();
        $newQuote['summary'] = $quote->getSummary();
        $newQuote['terms'] = $quote->getTerms();
        $newQuote = $this->parseContact($quote->getContact(), $newQuote);
        $newQuote = $this->parseLineItems($quote->getLineItems(), $newQuote);

        return $newQuote;
    }

    /**
     * Return all Quotes with Generic Schema Variable Assignment
     * @return array
     */
    public function getQuotations(){
        $quotes = [];
        if ($this->data instanceof Quote){
            $newQuote = $this->parseData($this->data);
            array_push($quotes, $newQuote);

        } else {
            foreach ($this->data as $quote) {
                $newQuote = $this->parseData($quote);
                array_push($quotes, $newQuote);
            }
        }

        return $quotes;
    }
}