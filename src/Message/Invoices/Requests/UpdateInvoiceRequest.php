<?php

namespace PHPAccounting\Xero\Message\Invoices\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Invoices\Requests\Traits\InvoiceRequestTrait;
use PHPAccounting\Xero\Message\Invoices\Responses\UpdateInvoiceResponse;
use PHPAccounting\Xero\Traits\AccountingIDRequestTrait;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Remote\Exception;

/**
 * Update Invoice(s)
 * @package PHPAccounting\XERO\Message\Invoices\Requests
 */
class UpdateInvoiceRequest extends AbstractXeroRequest
{
    use InvoiceRequestTrait, AccountingIDRequestTrait;

    public string $model = 'Invoice';

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
            $this->validate('type', 'contact', 'invoice_data', 'accounting_id');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('InvoiceID', 'accounting_id');
        $this->issetParam('Type', 'type');
        $this->issetParam('Date', 'date');
        $this->issetParam('DueDate', 'due_date');
        $this->issetParam('Contact', 'contact');
        $this->issetParam('LineItems', 'invoice_data');
        $this->issetParam('InvoiceNumber', 'invoice_number');
        $this->issetParam('Reference', 'invoice_reference');
        $this->issetParam('LineAmountType', 'gst_inclusive');

        if ($this->getStatus()) {
            $this->data['Status'] = $this->parseStatus($this->getStatus());
        }
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|UpdateInvoiceResponse
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            $response = parent::handleRequestException($data, 'InvalidRequestException');
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
                    // If either date or due date are empty, Xero will set default values
                    $methodName = 'set'. $key;
                    if ($value) {
                        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $value->toDateTimeString());
                        $invoice->$methodName($date);
                    }
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
        } catch(Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
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
