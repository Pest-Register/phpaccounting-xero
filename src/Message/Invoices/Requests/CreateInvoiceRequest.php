<?php

namespace PHPAccounting\Xero\Message\Invoices\Requests;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Invoices\Responses\CreateInvoiceResponse;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Invoice\LineItem;


class CreateInvoiceRequest extends AbstractRequest
{

    public function getType(){
        return $this->getParameter('type');
    }

    public function setType($value){
        return $this->setParameter('type', $value);
    }


    public function getData()
    {

        $this->issetParam('Type', 'type');
        $this->issetParam('Date', 'invoice_date');
        $this->issetParam('DueDate', 'invoice_due_date');

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
            $response = $invoice->save();

        } catch (\Exception $exception){
            $response = [
                'status' => 'error',
                'detail' => 'Exception when creating transaction: ', $exception->getMessage()
            ];
        }
        return $this->createResponse($response->getElements());
    }

    public function createResponse($data, $headers = [])
    {
        return $this->response = new CreateInvoiceResponse($this, $data, $headers);
    }

    private function addContactToInvoice(Invoice $invoice, $data){

    }

    private function addLineItemsToInvoice(Invoice $invoice, $data){
        foreach($data as $lineData) {
            $lineItem = new LineItem();
            $lineItem->setAccountCode(IndexSanityCheckHelper::indexSanityCheck('account_code', $lineData));
            $lineItem->setDescription(IndexSanityCheckHelper::indexSanityCheck('description', $lineData));
            $lineItem->setDiscountRate(IndexSanityCheckHelper::indexSanityCheck('discount', $lineData));
            $lineItem->setItemCode(IndexSanityCheckHelper::indexSanityCheck('item_code', $lineData));
            $lineItem->setLineItemID(IndexSanityCheckHelper::indexSanityCheck('item_id', $lineData));
            $lineItem->setLineAmount(IndexSanityCheckHelper::indexSanityCheck('amount', $lineData));
            $lineItem->setQuantity(IndexSanityCheckHelper::indexSanityCheck('quantity', $lineData));
            $lineItem->setUnitAmount(IndexSanityCheckHelper::indexSanityCheck('unit_amount', $lineData));
            $lineItem->setTaxAmount(IndexSanityCheckHelper::indexSanityCheck('tax_amount', $lineData));
            $lineItem->setTaxType(IndexSanityCheckHelper::indexSanityCheck('tax_type', $lineData));
            $invoice->addLineItem($lineItem);
        }
    }
}