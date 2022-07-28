<?php

namespace PHPAccounting\Xero\Message\Invoices\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

/**
 * Update Invoice(s) Response
 * @package PHPAccounting\XERO\Message\Invoices\Responses
 */
class UpdateInvoiceResponse extends AbstractResponse
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

    private function parseTaxCalculation($data)  {
        if ($data) {
            switch($data) {
                case 'Exclusive':
                    return 'EXCLUSIVE';
                case 'Inclusive':
                    return 'INCLUSIVE';
                case 'NoTax':
                    return 'NONE';
            }
        }
        return 'NONE';
    }

    /**
     * Add LineItems to Invoice
     * @param $data Array of LineItems
     * @param array $invoice Xero Invoice Object Mapping
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
                $newLineItem['item_code'] = IndexSanityCheckHelper::indexSanityCheck('ItemCode', $lineItem);
                $newLineItem['amount'] = IndexSanityCheckHelper::indexSanityCheck('LineAmount', $lineItem);
                $newLineItem['tax_amount'] = IndexSanityCheckHelper::indexSanityCheck('TaxAmount', $lineItem);
                $newLineItem['tax_type_id'] = IndexSanityCheckHelper::indexSanityCheck('TaxType', $lineItem);
                $newLineItem['code'] = IndexSanityCheckHelper::indexSanityCheck('AccountCode', $lineItem);
                array_push($lineItems, $newLineItem);
            }

            $invoice['invoice_data'] = $lineItems;
        }

        return $invoice;
    }

    /**
     * Add Contact to Invoice
     * @param $data Array of single Contact
     * @param array $invoice Xero Invoice Object Mapping
     * @return mixed
     */
    private function parseContact($data, $invoice) {
        if ($data) {
            $newContact = [];
            $newContact['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('ContactID',$data);
            $newContact['name'] = IndexSanityCheckHelper::indexSanityCheck('Name',$data);
            $invoice['contact'] = $newContact;
        }

        return $invoice;
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
                case 'PAID':
                    return $data;
                case 'SUBMITTED':
                case 'AUTHORISED':
                    return 'OPEN';
                case 'VOIDED':
                    return 'DELETED';
            }
        }
        return null;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getInvoices(){
        $invoices = [];
        foreach ($this->data as $invoice) {
            $newInvoice = [];
            $newInvoice['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('InvoiceID', $invoice);
            $newInvoice['status'] = $this->parseStatus(IndexSanityCheckHelper::indexSanityCheck('Status', $invoice));
            $newInvoice['sub_total'] = IndexSanityCheckHelper::indexSanityCheck('SubTotal', $invoice);
            $newInvoice['total_tax'] = IndexSanityCheckHelper::indexSanityCheck('TotalTax', $invoice);
            $newInvoice['total'] = IndexSanityCheckHelper::indexSanityCheck('Total', $invoice);
            $newInvoice['currency'] = IndexSanityCheckHelper::indexSanityCheck('CurrencyCode', $invoice);
            $newInvoice['type'] = IndexSanityCheckHelper::indexSanityCheck('Type', $invoice);
            $newInvoice['invoice_number'] = IndexSanityCheckHelper::indexSanityCheck('InvoiceNumber', $invoice);
            $newInvoice['amount_due'] = IndexSanityCheckHelper::indexSanityCheck('AmountDue', $invoice);
            $newInvoice['amount_paid'] = IndexSanityCheckHelper::indexSanityCheck('AmountPaid', $invoice);
            $newInvoice['currency_rate'] = IndexSanityCheckHelper::indexSanityCheck('CurrencyRate', $invoice);
            $newInvoice['discount_total'] = IndexSanityCheckHelper::indexSanityCheck('TotalDiscount', $invoice);
            $newInvoice['date'] = IndexSanityCheckHelper::indexSanityCheck('Date', $invoice);
            $newInvoice['gst_inclusive'] = $this->parseTaxCalculation(IndexSanityCheckHelper::indexSanityCheck('LineAmountTypes', $invoice));
            $newInvoice['updated_at'] = IndexSanityCheckHelper::indexSanityCheck('UpdatedDateUTC', $invoice);

            if (IndexSanityCheckHelper::indexSanityCheck('Contact', $invoice)) {
                $newInvoice = $this->parseContact($invoice['Contact'], $newInvoice);
            }
            if (IndexSanityCheckHelper::indexSanityCheck('LineItems', $invoice)) {
                $newInvoice = $this->parseLineItems($invoice['LineItems'], $newInvoice);
            }

            if (($newInvoice['amount_paid'] > 0 && $newInvoice['amount_due'] > 0) && $newInvoice['status'] !== 'DELETED') {
                $newInvoice['status'] = 'PARTIAL';
            }

            array_push($invoices, $newInvoice);
        }

        return $invoices;
    }
}