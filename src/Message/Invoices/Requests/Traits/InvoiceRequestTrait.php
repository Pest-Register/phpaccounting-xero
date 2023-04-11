<?php

namespace PHPAccounting\Xero\Message\Invoices\Requests\Traits;

use PHPAccounting\Xero\Helpers\IndexSanityInsertionHelper;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Invoice\LineItem;

trait InvoiceRequestTrait
{
    /**
     * Get GST Inclusive Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getGSTInclusive(){
        return $this->getParameter('gst_inclusive');
    }

    /**
     * Set GST Inclusive Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param string $value GST Inclusive
     */
    public function setGSTInclusive($value){
        return $this->setParameter('gst_inclusive', $value);
    }

    /**
     * Get Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getType(){
        return $this->getParameter('type');
    }

    /**
     * Set Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param string $value Invoice Type
     */
    public function setType($value){
        return $this->setParameter('type', $value);
    }

    /**
     * Get Invoice Reference Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function setInvoiceReference($value){
        return $this->setParameter('invoice_reference', $value);
    }

    /**
     * Get Invoice Reference Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getInvoiceReference(){
        return $this->getParameter('invoice_reference');
    }


    /**
     * Get Invoice number Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function setInvoiceNumber($value){
        return $this->setParameter('invoice_number', $value);
    }

    /**
     * Get Invoice number Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getInvoiceNumber(){
        return $this->getParameter('invoice_number');
    }

    /**
     * Get Invoice Data Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getInvoiceData(){
        return $this->getParameter('invoice_data');
    }

    /**
     * Set Invoice Data Parameter from Parameter Bag (LineItems)
     * @see https://developer.xero.com/documentation/api/invoices
     * @param array $value Invoice Item Lines
     */
    public function setInvoiceData($value){
        return $this->setParameter('invoice_data', $value);
    }

    /**
     * Get Status Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getStatus(){
        return $this->getParameter('status');
    }

    /**
     * Get Status Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }

    /**
     * Get Date Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getDate(){
        return $this->getParameter('date');
    }

    /**
     * Set Date Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param string $value Invoice date
     */
    public function setDate($value){
        return $this->setParameter('date', $value);
    }

    /**
     * Get Due Date Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getDueDate(){
        return $this->getParameter('due_date');
    }

    /**
     * Set Due Date Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param string $value Invoice Due Date
     */
    public function setDueDate($value){
        return $this->setParameter('due_date', $value);
    }

    /**
     * Get Contact Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getContact(){
        return $this->getParameter('contact');
    }

    /**
     * Set Contact Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param Contact $value Contact
     */
    public function setContact($value){
        return $this->setParameter('contact', $value);
    }

    /**
     * Add Contact to Invoice
     * @param Invoice $invoice Xero Invoice Object
     * @param string $data Contact ID
     */
    private function addContactToInvoice(Invoice $invoice, $data){
        $contact = new Contact();
        $contact->setContactID($data);
        $invoice->setContact($contact);
    }

    /**
     * Parse status
     * @param $data
     * @return string|null
     */
    private function parseStatus($data) {
        if ($data) {
            switch($data) {
                // Return Authorised as an auto-approval from PR
                case 'OPEN':
                    return 'AUTHORISED';
                case 'DELETED':
                    return 'VOIDED';
                case 'PAID':
                case 'DRAFT':
                case 'SUBMITTED':
                case 'AUTHORISED':
                    return $data;
            }
        }
        return null;
    }

    /**
     * Add Line Items to Invoice
     * @param Invoice $invoice Xero Invoice Object
     * @param array $data Array of Line Items
     */
    private function addLineItemsToInvoice(Invoice $invoice, $data){
        foreach($data as $lineData) {
            $lineItem = new LineItem();
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('code', $lineData, $lineItem, 'setAccountCode');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('description', $lineData, $lineItem, 'setDescription');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('discount_rate', $lineData, $lineItem, 'setDiscountRate');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('item_code', $lineData, $lineItem, 'setItemCode');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('accounting_id', $lineData, $lineItem, 'setLineItemID');
//            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('amount', $lineData, $lineItem, 'setLineAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('quantity', $lineData, $lineItem, 'setQuantity');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('unit_amount', $lineData, $lineItem, 'setUnitAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('tax_amount', $lineData, $lineItem, 'setTaxAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('tax_type_id', $lineData, $lineItem, 'setTaxType');
            $invoice->addLineItem($lineItem);
        }
    }
}