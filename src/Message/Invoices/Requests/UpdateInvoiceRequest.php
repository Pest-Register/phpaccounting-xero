<?php

namespace PHPAccounting\Xero\Message\Invoices\Requests;

use PHPAccounting\Xero\Helpers\IndexSanityInsertionHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Invoices\Responses\UpdateInvoiceResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Invoice\LineItem;

/**
 * Update Invoice(s)
 * @package PHPAccounting\XERO\Message\Invoices\Requests
 */
class UpdateInvoiceRequest extends AbstractRequest
{

    /**
     * Get Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getType(){
        return $this->getParameter('type');
    }

    /**
     * Set Type from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return UpdateInvoiceRequest
     */
    public function setType($value){
        return $this->setParameter('type', $value);
    }

    /**
     * Get InvoiceData Parameter from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getInvoiceData(){
        return $this->getParameter('invoice_data');
    }

    /**
     * Set Invoice Data from Parameter Bag (LineItems generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return UpdateInvoiceRequest
     */
    public function setInvoiceData($value){
        return $this->setParameter('invoice_data', $value);
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
     * Set Date from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return UpdateInvoiceRequest
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
     * Set Due Date from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return UpdateInvoiceRequest
     */
    public function setDueDate($value){
        return $this->setParameter('due_date', $value);
    }

    /**
     * Get ContactParameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getContact(){
        return $this->getParameter('contact');
    }

    /**
     * Set Contact from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return UpdateInvoiceRequest
     */
    public function setContact($value){
        return $this->setParameter('contact', $value);
    }

    /**
     * Set AccountingID from Parameter Bag (ContactID generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @param $value
     * @return UpdateInvoiceRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (InvoiceID generic interface)
     * @see https://developer.xero.com/documentation/api/invoices
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }

    /**
     * Add Contact to Invoice
     * @param Invoice $invoice Xero Invoice Object
     * @param array $data Array of Contact Objects
     */
    private function addContactToInvoice(Invoice $invoice, $data){
        $contact = new Contact();
        $contact->setContactID($data);
        $invoice->setContact($contact);
    }

    /**
     * Add LineItems to Invoice
     * @param Invoice $invoice Xero Invoice Object
     * @param array $data Array of LineItem Object mappings (Array)
     */
    private function addLineItemsToInvoice(Invoice $invoice, $data){
        foreach($data as $lineData) {
            $lineItem = new LineItem();
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('account_code', $lineData, $lineItem, 'setAccountCode');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('description', $lineData, $lineItem, 'setDescription');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('discount_rate', $lineData, $lineItem, 'setDiscountRate');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('item_code', $lineData, $lineItem, 'setItemCode');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('accounting_id', $lineData, $lineItem, 'setLineItemID');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('amount', $lineData, $lineItem, 'setLineAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('quantity', $lineData, $lineItem, 'setQuantity');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('unit_amount', $lineData, $lineItem, 'setUnitAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('tax_amount', $lineData, $lineItem, 'setTaxAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('tax_type', $lineData, $lineItem, 'setTaxType');
            $invoice->addLineItem($lineItem);
        }
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('type', 'contact', 'invoice_data', 'accounting_id');

        $this->issetParam('InvoiceID', 'accounting_id');
        $this->issetParam('Type', 'type');
        $this->issetParam('Date', 'date');
        $this->issetParam('DueDate', 'due_date');
        $this->issetParam('Contact', 'contact');
        $this->issetParam('LineItems', 'invoice_data');
        $this->issetParam('InvoiceNumber', 'invoice_number');
        $this->issetParam('Reference', 'invoice_reference');
        $this->issetParam('Status', 'invoice_status');
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|UpdateInvoiceResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();
            $xero->getOAuthClient()->setToken($this->getAccessToken());
            $xero->getOAuthClient()->setTokenSecret($this->getAccessTokenSecret());

            $invoice = new Invoice($xero);
            foreach ($data as $key => $value){
                if ($key === 'LineItems') {
                    $this->addLineItemsToInvoice($invoice, $value);
                } elseif ($key === 'Contact') {
                    $this->addContactToInvoice($invoice, $value);
                } elseif ($key === 'Date' || $key === 'DueDate') {
                    $methodName = 'set'. $key;
                    $invoice->$methodName(\DateTime::createFromFormat('Y-m-d', $value));
                } else {
                    $methodName = 'set'. $key;
                    $invoice->$methodName($value);
                }
            }
            $response = $invoice->save();
        } catch (\Exception $exception){
            $response = [
                'status' => 'error',
                'detail' => $exception->getMessage()
            ];
            return $this->createResponse($response);
        }
        return $this->createResponse($response->getElements());
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return UpdateInvoiceResponse
     */
    public function createResponse($data)
    {
        return $this->response = new UpdateInvoiceResponse($this, $data);
    }
}