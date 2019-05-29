<?php
/**
 * Created by IntelliJ IDEA.
 * User: Max
 * Date: 5/29/2019
 * Time: 5:37 PM
 */

namespace PHPAccounting\Xero\Message\Invoices\Responses;


use Omnipay\Common\Message\AbstractResponse;
use XeroPHP\Models\Accounting\Invoice;

class GetInvoiceResponse extends AbstractResponse
{
    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        if(array_key_exists('status', $this->data)){
            return !$this->data['status'] == 'error';
        }
        return true;
    }

    public function getErrorMessage(){
        if(array_key_exists('status', $this->data)){
            return $this->data['detail'];
        }
        return null;
    }

    /**
     * Create Generic Phones if Valid
     * @param $data
     * @param $invoice
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
                $newLineItem['quantity'] = $lineItem->getQuantity();
                $newLineItem['discount'] = $lineItem->getDiscountRate();
                $newLineItem['accounting_id'] = $lineItem->getLineItemID();
                $newLineItem['amount'] = $lineItem->getLineAmount();
                $newLineItem['account_code'] = $lineItem->getAccountCode();
                $newLineItem['item_code'] = $lineItem->getItemCode();
                $newLineItem['tax_amount'] = $lineItem->getTaxAmount();
                $newLineItem['tax_type'] = $lineItem->getTaxType();
                array_push($lineItems, $newLineItem);
            }

            $invoice['invoice_data'] = $lineItems;
        }

        return $invoice;
    }
    /**
     * Create Generic Phones if Valid
     * @param $data
     * @param $invoice
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
     * Return all Contacts with Generic Schema Variable Assignment
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
                $newInvoice = $this->parseContact($invoice->getContact(), $newInvoice);

                array_push($invoices, $newInvoice);
            }
        }


        return $invoices;
    }
}