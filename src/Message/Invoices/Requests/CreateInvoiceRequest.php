<?php

namespace PHPAccounting\Xero\Message\Invoices\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Helpers\IndexSanityInsertionHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Invoices\Responses\CreateInvoiceResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Invoice\LineItem;
use XeroPHP\Remote\Exception\UnauthorizedException;
use XeroPHP\Remote\Exception\BadRequestException;
use XeroPHP\Remote\Exception\ForbiddenException;
use XeroPHP\Remote\Exception\ReportPermissionMissingException;
use XeroPHP\Remote\Exception\NotFoundException;
use XeroPHP\Remote\Exception\InternalErrorException;
use XeroPHP\Remote\Exception\NotImplementedException;
use XeroPHP\Remote\Exception\RateLimitExceededException;
use XeroPHP\Remote\Exception\NotAvailableException;
use XeroPHP\Remote\Exception\OrganisationOfflineException;
/**
 * Create Invoice
 * @package PHPAccounting\XERO\Message\Invoices\Requests
 */
class CreateInvoiceRequest extends AbstractRequest
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
     * @return CreateInvoiceRequest
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
     * @return CreateInvoiceRequest
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
     * @return CreateInvoiceRequest
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
     * @return CreateInvoiceRequest
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
     * @return CreateInvoiceRequest
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
     * @return CreateInvoiceRequest
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
        try {
            $this->validate('type', 'contact', 'invoice_data');
        } catch (InvalidRequestException $exception) {
            return $exception;;
        }

        $this->issetParam('Type', 'type');
        $this->issetParam('Date', 'date');
        $this->issetParam('DueDate', 'due_date');
        $this->issetParam('Contact', 'contact');
        $this->issetParam('LineItems', 'invoice_data');
        $this->issetParam('InvoiceNumber', 'invoice_number');
        $this->issetParam('Reference', 'invoice_reference');
        $this->issetParam('Status', 'status');
        $this->issetParam('LineAmountType', 'gst_inclusive');
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|CreateInvoiceResponse
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            $response = [
                'status' => 'error',
                'type' => 'InvalidRequestException',
                'detail' => $data->getMessage(),
                'error_code' => $data->getCode(),
                'status_code' => $data->getCode(),
            ];
            return $this->createResponse($response);
        }
        try {
            $xero = $this->createXeroApplication();


            $invoice = new Invoice($xero);
            foreach ($data as $key => $value){
                if ($key === 'LineItems') {
                    $this->addLineItemsToInvoice($invoice, $value);
                } elseif ($key === 'Contact') {
                    $this->addContactToInvoice($invoice, $value);
                } elseif ($key === 'Date' || $key === 'DueDate') {
                    $methodName = 'set'. $key;
                    $date = \DateTime::createFromFormat('Y-m-d H:i:s', $value->toDateTimeString());
                    $invoice->$methodName($date);
                } else if ($key === 'LineAmountType') {
                    $methodName = 'set'.$key;
                    if ($value === 'EXCLUSIVE') {
                        $invoice->$methodName('Exclusive');
                    }
                    else if ($value === 'INCLUSIVE') {
                        $invoice->$methodName('Inclusive');
                    } else {
                        $invoice->$methodName('NoTax');
                    }
                } else if($key === 'Status') {
                    $methodName = 'set'.$key;
                    $invoice->$methodName($value);
                    if ($value === 'AUTHORISED') {
                        $invoice->setSentToContact(false);
                    }
                } else {
                    $methodName = 'set'. $key;
                    $invoice->$methodName($value);
                }
            }
            $response = $xero->save($invoice);
        } catch (BadRequestException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'BadRequest',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (UnauthorizedException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Unauthorized',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (ForbiddenException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Forbidden',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (ReportPermissionMissingException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'ReportPermissionMissingException',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (NotFoundException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotFound',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (InternalErrorException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'Internal',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (NotImplementedException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotImplemented',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (RateLimitExceededException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'RateLimitExceeded',
                'rate_problem' => $exception->getRateLimitProblem(),
                'retry' => $exception->getRetryAfter(),
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (NotAvailableException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'NotAvailable',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        } catch (OrganisationOfflineException $exception) {
            $response = [
                'status' => 'error',
                'type' => 'OrganisationOffline',
                'detail' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
                'status_code' => $exception->getCode(),
            ];

            return $this->createResponse($response);
        }
        return $this->createResponse($response->getElements());
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return CreateInvoiceResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateInvoiceResponse($this, $data);
    }


}