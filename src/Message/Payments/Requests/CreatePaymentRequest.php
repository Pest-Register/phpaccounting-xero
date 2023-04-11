<?php

namespace PHPAccounting\Xero\Message\Payments\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Payments\Requests\Traits\PaymentRequestTrait;
use PHPAccounting\Xero\Message\Payments\Responses\CreatePaymentResponse;
use XeroPHP\Models\Accounting\Payment;
use XeroPHP\Remote\Exception;

/**
 * Create Invoice
 * @package PHPAccounting\XERO\Message\Invoices\Requests
 */
class CreatePaymentRequest extends AbstractXeroRequest
{
    use PaymentRequestTrait;

    public string $model = 'Payment';

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
            $this->validate('account', 'amount', 'date');
        } catch (InvalidRequestException $exception) {
            return $exception;
        }

        $this->issetParam('Account', 'account');
        $this->issetParam('Invoice', 'invoice');
        $this->issetParam('CreditNote', 'credit_note');
        $this->issetParam('Prepayment', 'prepayment');
        $this->issetParam('Overpayment', 'overpayment');
        $this->issetParam('Date', 'date');
        $this->issetParam('CurrencyRate', 'currency_rate');
        $this->issetParam('Amount', 'amount');
        $this->issetParam('Reference', 'reference_id');
        $this->issetParam('IsReconciled', 'is_reconciled');
        $this->issetParam('Status', 'status');
        return $this->data;
    }


    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
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
                if ($key === 'Account') {
                    $this->addAccountToPayment($payment, $value);
                } elseif ($key === 'Invoice') {
                    $this->addInvoiceToPayment($payment, $value);
                } elseif ($key === 'CreditNote') {
                    $this->addCreditNoteToPayment($payment, $value);
                } elseif ($key === 'Prepayment') {
                    $this->addPrepaymentToPayment($payment, $value);
                } elseif ($key === 'Overpayment') {
                    $this->addOverpaymentToPayment($payment, $value);
                } elseif ($key === 'Date') {
                    $methodName = 'set'. $key;
                    $date = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
                    $payment->$methodName($date);
                } elseif ($key === 'IsReconciled') {
                    $methodName = 'set'.$key;
                    $isReconciled = $value ? 'true' : 'false';
                    $payment->$methodName($isReconciled);
                } else {
                    $methodName = 'set'. $key;
                    $payment->$methodName($value);
                }
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
     * @return CreatePaymentResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreatePaymentResponse($this, $data);
    }


}
