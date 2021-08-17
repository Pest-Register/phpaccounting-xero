<?php


namespace PHPAccounting\Xero\Message\Quotations\Requests;


use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Helpers\IndexSanityInsertionHelper;
use PHPAccounting\Xero\Message\AbstractRequest;

use PHPAccounting\Xero\Message\Quotations\Responses\CreateQuotationResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\LineItem;
use XeroPHP\Models\Accounting\Quote;
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
 * Create Quotation
 * @package PHPAccounting\XERO\Message\Quotations\Requests
 */
class CreateQuotationRequest extends AbstractRequest
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
     * @return CreateQuotationRequest
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
     * @return CreateQuotationRequest
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
     * @return CreateQuotationRequest
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
     * @return CreateQuotationRequest
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
     * @return CreateQuotationRequest
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
     * @return CreateQuotationRequest
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
     * @return CreateQuotationRequest
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
     * @return CreateQuotationRequest
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
//            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('amount', $lineData, $lineItem, 'setLineAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('quantity', $lineData, $lineItem, 'setQuantity');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('unit_amount', $lineData, $lineItem, 'setUnitAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('tax_amount', $lineData, $lineItem, 'setTaxAmount');
            $lineItem = IndexSanityInsertionHelper::indexSanityInsert('tax_type', $lineData, $lineItem, 'setTaxType');
            $quote->addLineItem($lineItem);
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
            $this->validate('contact', 'quotation_data');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('Date', 'date');
        $this->issetParam('ExpiryDate', 'expiry_date');
        $this->issetParam('Contact', 'contact');
        $this->issetParam('LineItems', 'quotation_data');
        $this->issetParam('QuoteNumber', 'quotation_number');
        $this->issetParam('Reference', 'quotation_reference');
        $this->issetParam('Status', 'status');
        $this->issetParam('LineAmountType', 'gst_inclusive');
        $this->issetParam('Title', 'title');
        $this->issetParam('Summary', 'summary');
        $this->issetParam('Terms', 'terms');
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|CreateQuotationResponse
     * @throws \XeroPHP\Exception
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

            $quote = new Quote($xero);
            foreach ($data as $key => $value) {
                if ($key === 'LineItems') {
                    $this->addLineItemsToQuotation($quote, $value);
                } elseif ($key === 'Contact') {
                    $this->addContactToQuotation($quote, $value);
                } elseif ($key === 'Date' || $key === 'ExpiryDate') {
                    $methodName = 'set'.$key;
                    $date = \DateTime::createFromFormat('Y-m-d H:i:s', $value->toDateTimeString());
                    $quote->$methodName($date);
                } else if ($key === 'LineAmountType') {
                    $methodName = 'set'.$key;
                    if ($value === 'EXCLUSIVE') {
                        $quote->$methodName('Exclusive');
                    }
                    else if ($value === 'INCLUSIVE') {
                        $quote->$methodName('Inclusive');
                    } else {
                        $quote->$methodName('NoTax');
                    }
                } else if($key === 'Status') {
                    $methodName = 'set'.$key;
                    $quote->$methodName($value);
                    if ($value === 'DRAFT') {
                        $quote->setSentToContact(false);
                    }
                } else {
                    $methodName = 'set'. $key;
                    $quote->$methodName($value);
                }
            }
            $response = $xero->save($quote);
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
     * @return CreateQuotationResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateQuotationResponse($this, $data);
    }
}