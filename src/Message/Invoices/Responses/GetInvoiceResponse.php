<?php

namespace PHPAccounting\Xero\Message\Invoices\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;
use vendor\project\StatusTest;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Payment;

/**
 * Get Invoice(s) Response
 * @package PHPAccounting\XERO\Message\Invoices\Responses
 */
class GetInvoiceResponse extends AbstractXeroResponse
{

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
                $newLineItem['discount_amount'] = $lineItem->getDiscountAmount();
                $newLineItem['item_code'] = $lineItem->getItemCode();
                $newLineItem['tax_amount'] = $lineItem->getTaxAmount();
                $newLineItem['tax_type_id'] = $lineItem->getTaxType();
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


    private function parsePayments($data, $invoice) {
        if ($data) {
            $payments = [];
            foreach($data as $payment) {
                $newPayment = [];
                $newPayment['accounting_id'] = $payment->getPaymentID();
                $newPayment['date'] = $payment->getDate();
                $newPayment['amount'] = $payment->getAmount();
                $newPayment['reference_id'] = $payment->getReference();
                $newPayment['currency_rate'] = $payment->getCurrencyRate() ?: 1.0;
                $newPayment['type'] = $payment->getPaymentType();
                $newPayment['status'] = $payment->getStatus();
                $newPayment['is_reconciled'] = $payment->getIsReconciled();
                $newPayment['updated_at'] = $payment->getUpdatedDateUTC();
                array_push($payments, $newPayment);
            }

            $invoice['payments'] = $payments;
        }

        return $invoice;
    }

    /**
     *
     * @param $invoice
     * @return mixed
     */
    private function parseData($invoice) {
        $newInvoice = [];
        $newInvoice['accounting_id'] = $invoice->getInvoiceID();
        $newInvoice['status'] = $this->parseStatus($invoice->getStatus());
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
        $newInvoice['gst_inclusive'] = $this->parseTaxCalculation($invoice->getLineAmountTypes());
        $newInvoice['updated_at'] = $invoice->getUpdatedDateUTC();
        $newInvoice = $this->parseContact($invoice->getContact(), $newInvoice);
        $newInvoice = $this->parseLineItems($invoice->getLineItems(), $newInvoice);
        $newInvoice = $this->parsePayments($invoice->getPayments(), $newInvoice);

        if (($newInvoice['amount_paid'] > 0 && $newInvoice['amount_due'] > 0) && $newInvoice['status'] !== 'DELETED') {
            $newInvoice['status'] = 'PARTIAL';
        }

        return $newInvoice;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getInvoices(){
        $invoices = [];
        if ($this->data instanceof Invoice){
            $newInvoice = $this->parseData($this->data);
            array_push($invoices, $newInvoice);

        } else {
            foreach ($this->data as $invoice) {
                $newInvoice = $this->parseData($invoice);
                array_push($invoices, $newInvoice);
            }
        }

        return $invoices;
    }
}
