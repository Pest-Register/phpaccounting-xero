<?php

namespace PHPAccounting\Xero\Message\Invoices\Requests;

use PHPAccounting\Xero\Helpers\IndexSanityInsertionHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Invoices\Responses\CreateInvoiceResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Invoice\LineItem;

/**
 * Class CreateInvoiceRequest
 * @package PHPAccounting\Xero\Message\Invoices\Requests
 */

class CreateInvoiceRequest extends AbstractRequest
{

    /**
     * @return mixed
     */
    public function getType(){
        return $this->getParameter('type');
    }

    /**
     * @param $value
     * @return CreateInvoiceRequest
     */
    public function setType($value){
        return $this->setParameter('type', $value);
    }

    /**
     * @return mixed
     */

    public function getInvoiceData(){
        return $this->getParameter('invoice_data');
    }

    /**
     * @param $value
     * @return CreateInvoiceRequest
     */

    public function setInvoiceData($value){
        return $this->setParameter('invoice_data', $value);
    }

    /**
     * @return mixed
     */

    public function getDate(){
        return $this->getParameter('date');
    }

    /**
     * @param $value
     * @return CreateInvoiceRequest
     */

    public function setDate($value){
        return $this->setParameter('date', $value);
    }

    /**
     * @return mixed
     */
    public function getDueDate(){
        return $this->getParameter('due_date');
    }

    /**
     * @param $value
     * @return CreateInvoiceRequest
     */
    public function setDueDate($value){
        return $this->setParameter('due_date', $value);
    }

    /**
     * @return mixed
     */
    public function getContact(){
        return $this->getParameter('contact');
    }

    /**
     * @param $value
     * @return CreateInvoiceRequest
     */
    public function setContact($value){
        return $this->setParameter('contact', $value);
    }

    /**
     * @param Invoice $invoice
     * @param $data
     */
    private function addContactToInvoice(Invoice $invoice, $data){
        $contact = new Contact();
        $contact->setContactID($data);
        $invoice->setContact($contact);
    }

    /**
     * @param Invoice $invoice
     * @param $data
     */

    private function addLineItemsToInvoice(Invoice $invoice, $data){
        foreach($data as $lineData) {
            $lineItem = new LineItem();
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('account_code', $lineData, $lineItem, 'setAccountCode');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('description', $lineData, $lineItem, 'setDescription');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('discount', $lineData, $lineItem, 'setDiscountRate');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('item_code', $lineData, $lineItem, 'setItemCode');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('accounting_id', $lineData, $lineItem, 'setLineItemID');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('amount', $lineData, $lineItem, 'setAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('quantity', $lineData, $lineItem, 'setQuantity');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('unit_amount', $lineData, $lineItem, 'setUnitAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('tax_amount', $lineData, $lineItem, 'setTaxAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('tax_type', $lineData, $lineItem, 'setTaxType');
            $invoice->addLineItem($lineItem);
        }
    }

    /**
     * @return array|mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('type', 'contact', 'invoice_data');

        $this->issetParam('Type', 'type');
        $this->issetParam('Date', 'date');
        $this->issetParam('DueDate', 'due_date');
        $this->issetParam('Contact', 'contact');
        $this->issetParam('LineItems', 'invoice_data');
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return \Omnipay\Common\Message\ResponseInterface|CreateInvoiceResponse
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
     * @param $data
     * @return CreateInvoiceResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateInvoiceResponse($this, $data);
    }


}