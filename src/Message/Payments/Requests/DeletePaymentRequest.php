<?php

namespace PHPAccounting\Xero\Message\Payments\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Payments\Responses\DeletePaymentResponse;
use PHPAccounting\Xero\Traits\AccountingIDRequestTrait;
use XeroPHP\Models\Accounting\Payment;
use XeroPHP\Remote\Exception;

/**
 * Delete Invoice
 * @package PHPAccounting\XERO\Message\Invoices\Requests
 */
class DeletePaymentRequest extends AbstractXeroRequest
{
    use AccountingIDRequestTrait;

    public string $model = 'Payment';

    /**
     * Set Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/invoices
     * @param string $value Contact Name
     * @return DeletePaymentRequest
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
            return $exception;;
        }
        $this->issetParam('PaymentID', 'accounting_id');
        $this->issetParam('Status', 'status');
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|DeletePaymentResponse
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            $response = parent::handleRequestException($data, 'InvalidRequestException');
            return $this->createResponse($response);
        }
        try {
            $xero = $this->createXeroApplication();


            $payment = new Payment($xero);
            foreach ($data as $key => $value){
                $methodName = 'set'. $key;
                $payment->$methodName($value);
            }

            $response = $xero->save($payment);

        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }

        return $this->createResponse($response->getElements());
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return DeletePaymentResponse
     */
    public function createResponse($data)
    {
        return $this->response = new DeletePaymentResponse($this, $data);
    }
}
