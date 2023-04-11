<?php

namespace PHPAccounting\Xero\Message\Quotations\Requests\Traits;

use PHPAccounting\Xero\Helpers\IndexSanityInsertionHelper;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\LineItem;
use XeroPHP\Models\Accounting\Quote;

trait QuotationRequestTrait
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
     * @see https://developer.xero.com/documentation/api/quotes
     * @param string $value GST Inclusive
     */
    public function setGSTInclusive($value){
        return $this->setParameter('gst_inclusive', $value);
    }

    /**
     * Get Quote Reference Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/quotes
     * @return mixed
     */
    public function setQuotationReference($value){
        return $this->setParameter('quotation_reference', $value);
    }

    /**
     * Get Invoice Reference Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/quotes
     * @return mixed
     */
    public function getQuotationReference(){
        return $this->getParameter('quotation_reference');
    }


    /**
     * Get Quotation number Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/quotes
     * @return mixed
     */
    public function setQuotationNumber($value){
        return $this->setParameter('quotation_number', $value);
    }

    /**
     * Get Quotation number Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/quotes
     * @return mixed
     */
    public function getQuotationNumber(){
        return $this->getParameter('quotation_number');
    }

    /**
     * Get Quotation Data Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/quotes
     * @return mixed
     */
    public function getQuotationData(){
        return $this->getParameter('quotation_data');
    }

    /**
     * Set Quotation Data Parameter from Parameter Bag (LineItems)
     * @see https://developer.xero.com/documentation/api/quotes
     * @param array $value Quotation Item Lines
     */
    public function setQuotationData($value){
        return $this->setParameter('quotation_data', $value);
    }

    /**
     * Get Status Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/quotes
     * @return mixed
     */
    public function getStatus(){
        return $this->getParameter('status');
    }

    /**
     * Get Status Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/quotes
     * @return mixed
     */
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }

    /**
     * Get Date Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/quotes
     * @return mixed
     */
    public function getDate(){
        return $this->getParameter('date');
    }

    /**
     * Set Date Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/quotes
     * @param string $value Quotation date
     */
    public function setDate($value){
        return $this->setParameter('date', $value);
    }

    /**
     * Get Expiry Date Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/quotes
     * @return mixed
     */
    public function getExpiryDate(){
        return $this->getParameter('expiry_date');
    }

    /**
     * Set Expiry Date Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/quotes
     * @param string $value Quotation Expiry Date
     */
    public function setExpiryDate($value){
        return $this->setParameter('expiry_date', $value);
    }

    /**
     * Get Contact Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/quotes
     * @return mixed
     */
    public function getContact(){
        return $this->getParameter('contact');
    }

    /**
     * Set Contact Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/quotes
     * @param Contact $value Contact
     */
    public function setContact($value){
        return $this->setParameter('contact', $value);
    }

    /**
     * Get Title Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/quotes
     * @return mixed
     */
    public function getTitle(){
        return $this->getParameter('title');
    }

    /**
     * Set Title Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/quotes
     * @param string $value Title
     */
    public function setTitle($value){
        return $this->setParameter('title', $value);
    }

    /**
     * Get Summary Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/quotes
     * @return mixed
     */
    public function getSummary(){
        return $this->getParameter('summary');
    }

    /**
     * Set Summary Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/quotes
     * @param string $value Summary
     */
    public function setSummary($value){
        return $this->setParameter('summary', $value);
    }

    /**
     * Get Terms Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/quotes
     * @return mixed
     */
    public function getTerms(){
        return $this->getParameter('terms');
    }

    /**
     * Set Terms Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/quotes
     * @param string $value Terms
     */
    public function setTerms($value){
        return $this->setParameter('terms', $value);
    }

    /**
     * Add Contact to Invoice
     * @param Quote $quote Xero Quote Object
     * @param string $data Contact ID
     */
    private function addContactToQuotation(Quote $quote, $data){
        $contact = new Contact();
        $contact->setContactID($data);
        $quote->setContact($contact);
    }

    /**
     * Add Line Items to Quote
     * @param Quote $quote
     * @param array $data Array of Line Items
     */
    private function addLineItemsToQuotation(Quote $quote, $data){
        foreach($data as $lineData) {
            $lineItem = new LineItem();
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('code', $lineData, $lineItem, 'setAccountCode');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('description', $lineData, $lineItem, 'setDescription');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('discount_rate', $lineData, $lineItem, 'setDiscountRate');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('item_code', $lineData, $lineItem, 'setItemCode');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('accounting_id', $lineData, $lineItem, 'setLineItemID');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('quantity', $lineData, $lineItem, 'setQuantity');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('unit_amount', $lineData, $lineItem, 'setUnitAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('tax_amount', $lineData, $lineItem, 'setTaxAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('tax_type_id', $lineData, $lineItem, 'setTaxType');
            $quote->addLineItem($lineItem);
        }
    }

    /**
     * Parse status
     * @param $data
     * @return string|null
     */
    private function parseStatus($data) {
        if ($data) {
            switch($data) {
                case 'REJECTED':
                    return 'DECLINED';
                case 'SENT':
                case 'DRAFT':
                case 'DELETED':
                case 'ACCEPTED':
                    return $data;
            }
        }
        return null;
    }
}