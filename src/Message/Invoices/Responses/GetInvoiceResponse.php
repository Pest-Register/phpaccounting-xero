<?php

namespace PHPAccounting\Xero\Message\Invoices\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use vendor\project\StatusTest;
use XeroPHP\Models\Accounting\Invoice;

/**
 * Get Invoice(s) Response
 * @package PHPAccounting\XERO\Message\Invoices\Responses
 */
class GetInvoiceResponse extends AbstractResponse
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
     * @return string
     */
    public function getErrorMessage(){
        if ($this->data) {
            if(array_key_exists('status', $this->data)){
                return ErrorResponseHelper::parseErrorResponse($this->data['detail'],$this->data['type'],$this->data, 'Invoice');
            }
            if (count($this->data) === 0) {
                return 'NULL Returned from API or End of Pagination';
            }
        }
        return null;
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
                $newLineItem['tax_type'] = $lineItem->getTaxType();
                $newLineItem['code'] = $lineItem->getAccountCode();
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
            $newContact['accounting_id'] = $data->getContactID();
            $newContact['name'] = $data->getName();
            $invoice['contact'] = $newContact;
        }

        return $invoice;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getInvoices(){
        $invoices = [];
        if ($this->data instanceof Invoice){
            $invoice = $this->data;
            $newInvoice = [];
            $newInvoice['accounting_id'] = $invoice->getInvoiceID();
            $newInvoice['status'] = $invoice->getStatus();
            $newInvoice['sub_total'] = $invoice->getSubTotal();
            $newInvoice['total_tax'] = $invoice->getTotalTax();
            $newInvoice['total'] = $invoice->getTotal();
            $newInvoice['currency'] = $invoice->getCurrencyCode();
            $newInvoice['type'] = $invoice->getType();
            $newInvoice['invoice_number'] = $invoice->getInvoiceNumber();
            $newInvoice['amount_due'] = $invoice->getAmountDue();
            $newInvoice['amount_paid'] = $invoice->getAmountPaid();
            $newInvoice['currency_rate'] = $invoice->getCurrencyRate();
            $newInvoice['date'] = $invoice->getDate();
            $newInvoice['due_date'] = $invoice->getDueDate();
            $newInvoice['gst_inclusive'] = $invoice->getLineAmountTypes();
            $newInvoice['updated_at'] = $invoice->getUpdatedDateUTC();
            $newInvoice = $this->parseContact($invoice->getContact(), $newInvoice);
            $newInvoice = $this->parseLineItems($invoice->getLineItems(), $newInvoice);

            array_push($invoices, $newInvoice);

        } else {
            foreach ($this->data as $invoice) {
                $newInvoice = [];
                $newInvoice['accounting_id'] = $invoice->getInvoiceID();
                $newInvoice['status'] = $invoice->getStatus();
                $newInvoice['sub_total'] = $invoice->getSubTotal();
                $newInvoice['total_tax'] = $invoice->getTotalTax();
                $newInvoice['total'] = $invoice->getTotal();
                $newInvoice['currency'] = $invoice->getCurrencyCode();
                $newInvoice['type'] = $invoice->getType();
                $newInvoice['invoice_number'] = $invoice->getInvoiceNumber();
                $newInvoice['amount_due'] = $invoice->getAmountDue();
                $newInvoice['amount_paid'] = $invoice->getAmountPaid();
                $newInvoice['currency_rate'] = $invoice->getCurrencyRate();
                $newInvoice['date'] = $invoice->getDate();
                $newInvoice['due_date'] = $invoice->getDueDate();
                $newInvoice['gst_inclusive'] = $invoice->getLineAmountTypes();
                $newInvoice['updated_at'] = $invoice->getUpdatedDateUTC();
                $newInvoice = $this->parseContact($invoice->getContact(), $newInvoice);
                $newInvoice = $this->parseLineItems($invoice->getLineItems(), $newInvoice);

                array_push($invoices, $newInvoice);
            }
        }


        return $invoices;
    }
}