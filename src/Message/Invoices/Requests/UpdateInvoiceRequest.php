<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 14/05/2019
 * Time: 12:46 PM
 */

namespace PHPAccounting\Xero\Message\Invoices\Requests;




use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\XERO\Message\Invoices\Responses\UpdateInvoiceResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Invoice\LineItem;

class UpdateInvoiceRequest extends AbstractRequest
{

    public function getInvoiceId(){
        return $this->getParameter('accounting_id');
    }

    public function setInvoiceId($value){
        return $this-$this->setParameter('accounting_id', $value);
    }

    public function getType(){
        return $this->getParameter('type');
    }

    public function setType($value){
        return $this->setParameter('type', $value);
    }

    public function getInvoiceData(){
        return $this->getParameter('invoice_data');
    }

    public function setInvoiceData($value){
        return $this->setParameter('invoice_data', $value);
    }

    public function getDate(){
        return $this->getParameter('date');
    }
    public function setDate($value){
        return $this->setParameter('date', $value);
    }
    public function getDueDate(){
        return $this->getParameter('due_date');
    }
    public function setDueDate($value){
        return $this->setParameter('due_date', $value);
    }
    public function getContact(){
        return $this->getParameter('contact');
    }
    public function setContact($value){
        return $this->setParameter('contact', $value);
    }


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
            var_dump($invoice->validate());
            $response = $invoice->save();
        } catch (\Exception $exception){
            var_dump($exception->getMessage());
            $response = [
                'status' => 'error',
                'detail' => $exception->getMessage()
            ];
            return $this->createResponse($response);
        }
        return $this->createResponse($response->getElements());
    }

    public function createResponse($data)
    {
        return $this->response = new UpdateInvoiceResponse($this, $data);
    }

    private function addContactToInvoice(Invoice $invoice, $data){
        $contact = new Contact();
        $contact->setContactID($data);
        $invoice->setContact($contact);
    }

    private function addLineItemsToInvoice(Invoice $invoice, $data){
        foreach($data as $lineData) {
            $lineItem = new LineItem();
            $lineItem->setAccountCode(IndexSanityCheckHelper::indexSanityCheck('account_code', $lineData));
            $lineItem->setDescription(IndexSanityCheckHelper::indexSanityCheck('description', $lineData));
            $lineItem->setDiscountRate(IndexSanityCheckHelper::indexSanityCheck('discount', $lineData));
            $lineItem->setItemCode(IndexSanityCheckHelper::indexSanityCheck('item_code', $lineData));
            $lineItem->setLineItemID(IndexSanityCheckHelper::indexSanityCheck('accounting_id', $lineData));
            $lineItem->setLineAmount(IndexSanityCheckHelper::indexSanityCheck('amount', $lineData));
            $lineItem->setQuantity(IndexSanityCheckHelper::indexSanityCheck('quantity', $lineData));
            $lineItem->setUnitAmount(IndexSanityCheckHelper::indexSanityCheck('unit_amount', $lineData));
            $lineItem->setTaxAmount(IndexSanityCheckHelper::indexSanityCheck('tax_amount', $lineData));
            $lineItem->setTaxType(IndexSanityCheckHelper::indexSanityCheck('tax_type', $lineData));
            $invoice->addLineItem($lineItem);
        }
    }
}