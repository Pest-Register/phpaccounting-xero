<?php

namespace PHPAccounting\Xero\Message\Invoices\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Invoices\Responses\DeleteInvoiceResponse;
use PHPAccounting\Xero\Message\Traits\AccountingIDRequestTrait;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Remote\Exception;

/**
 * Delete Invoice
 * @package PHPAccounting\XERO\Message\Invoices\Requests
 */
class DeleteInvoiceRequest extends AbstractXeroRequest
{
    use AccountingIDRequestTrait;

    public string $model = 'Invoice';

    /**
     * Set Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param string $value Contact Name
     * @return DeleteInvoiceRequest
     */
    public function setStatus($value) {
        return  $this->setParameter('status', $value);
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
            $this->validate('accounting_id');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }
        $this->issetParam('InvoiceID', 'accounting_id');
        $this->issetParam('Status', 'status');
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|DeleteInvoiceResponse
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
                $methodName = 'set'. $key;
                $invoice->$methodName($value);
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
     * @return DeleteInvoiceResponse
     */
    public function createResponse($data)
    {
        return $this->response = new DeleteInvoiceResponse($this, $data);
    }
}
