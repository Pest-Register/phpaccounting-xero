<?php

namespace PHPAccounting\Xero\Message\Payments\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Helpers\IndexSanityInsertionHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Payments\Responses\CreatePaymentResponse;
use XeroPHP\Models\Accounting\Account;
use XeroPHP\Models\Accounting\CreditNote;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Overpayment;
use XeroPHP\Models\Accounting\Payment;
use XeroPHP\Models\Accounting\Prepayment;
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
class CreatePaymentRequest extends AbstractRequest
{

    /**
     * Get Amount Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @return mixed
     */
    public function getAmount(){
        return $this->getParameter('amount');
    }

    /**
     * Set Amount Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @param string $value Payment Amount
     * @return CreatePaymentRequest
     */
    public function setAmount($value){
        return $this->setParameter('amount', $value);
    }

    /**
     * Get Currency Rate Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @return mixed
     */
    public function getCurrencyRate(){
        return $this->getParameter('currency_rate');
    }

    /**
     * Set Amount Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @param string $value Payment Currency Rate
     * @return CreatePaymentRequest
     */
    public function setCurrencyRate($value){
        return $this->setParameter('currency_rate', $value);
    }

    /**
     * Get Currency Rate Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @return mixed
     */
    public function getReferenceID(){
        return $this->getParameter('reference_id');
    }

    /**
     * Set Amount Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @param string $value Payment Reference ID
     * @return CreatePaymentRequest
     */
    public function setReferenceID($value){
        return $this->setParameter('reference_id', $value);
    }

    /**
     * Get Is Reconciled Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @return mixed
     */
    public function getIsReconciled(){
        return $this->getParameter('is_reconciled');
    }

    /**
     * Set Is Reconciled Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @param string $value Payment Is Reconcile
     * @return CreatePaymentRequest
     */
    public function setIsReconciled($value){
        return $this->setParameter('is_reconciled', $value);
    }

    /**
     * Get Invoice Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @return mixed
     */
    public function getInvoice(){
        return $this->getParameter('invoice');
    }

    /**
     * Set Invoice Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @param string $value Invoice
     * @return CreatePaymentRequest
     */
    public function setInvoice($value){
        return $this->setParameter('invoice', $value);
    }

    /**
     * Get Account Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @return mixed
     */
    public function getAccount(){
        return $this->getParameter('account');
    }

    /**
     * Set Account Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @param string $value Invoice
     * @return CreatePaymentRequest
     */
    public function setAccount($value){
        return $this->setParameter('account', $value);
    }

    /**
     * Get Credit Note Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @return mixed
     */
    public function getCreditNote(){
        return $this->getParameter('credit_note');
    }

    /**
     * Set Credit Note Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @param string $value CreditNote
     * @return CreatePaymentRequest
     */
    public function setCreditNote($value){
        return $this->setParameter('credit_note', $value);
    }

    /**
     * Get Prepayment Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @return mixed
     */
    public function getPrepayment(){
        return $this->getParameter('prepayment');
    }

    /**
     * Set Prepayment Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @param string $value Prepayment
     * @return CreatePaymentRequest
     */
    public function setPrepayment($value){
        return $this->setParameter('prepayment', $value);
    }

    /**
     * Get Overpayment Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @return mixed
     */
    public function getOverpayment(){
        return $this->getParameter('overpayment');
    }

    /**
     * Set Overpayment Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @param string $value Overpayment
     * @return CreatePaymentRequest
     */
    public function setOverpayment($value){
        return $this->setParameter('overpayment', $value);
    }

    /**
     * Get Date Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @return mixed
     */
    public function getDate(){
        return $this->getParameter('date');
    }

    /**
     * Set Date Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/payments
     * @param string $value Date
     * @return CreatePaymentRequest
     */
    public function setDate($value){
        return $this->setParameter('date', $value);
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
            $this->validate('account', 'amount', 'date');
        } catch (InvalidRequestException $exception) {
            return $exception;;
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
     * @param Payment $payment
     * @param $value
     * @return Payment
     */
    private function addOverpaymentToPayment(Payment $payment, $value) {
        if (array_key_exists('accounting_id', $value)) {
            $overpayment = new Overpayment();
            $overpayment->setOverpaymentID($value['accounting_id']);
            $payment->setOverpayment($overpayment);
        }
    }
    /**
     * @param Payment $payment
     * @param $value
     * @return Payment
     */
    private function addCreditNoteToPayment(Payment $payment, $value) {
        if (array_key_exists('accounting_id', $value)) {
            $creditNote = new CreditNote();
            $creditNote->setCreditNoteID($value['accounting_id']);
            $payment->setCreditNote($creditNote);
        } elseif (array_key_exists('credit_note_number', $value)) {
            $creditNote = new CreditNote();
            $creditNote->setCreditNoteNumber($value['credit_note_number']);
            $payment->setCreditNote($creditNote);
        }
    }

    /**
     * @param Payment $payment
     * @param $value
     * @return Payment
     */
    private function addAccountToPayment(Payment $payment, $value) {
        if (array_key_exists('accounting_id', $value)) {
            $account = new Account();
            $account->setAccountID($value['accounting_id']);
            $payment->setAccount($account);
        } else if (array_key_exists('code', $value)) {
            $account = new Account();
            $account->setCode($value['code']);
            $payment->setAccount($account);
        }
    }

    /**
     * @param Payment $payment
     * @param $value
     * @return Payment
     */
    private function addInvoiceToPayment(Payment $payment, $value) {
        if (array_key_exists('accounting_id', $value)) {
            $invoice = new Invoice();
            $invoice->setInvoiceID($value['accounting_id']);
            $payment->setInvoice($invoice);
        } else if (array_key_exists('invoice_number', $value)) {
            $invoice = new Invoice();
            $invoice->setInvoiceNumber($value['invoice_number']);
            $payment->setInvoice($invoice);
        }
    }

    /**
     * @param Payment $payment
     * @param $value
     * @return Payment
     */
    private function addPrepaymentToPayment(Payment $payment, $value) {
        if (array_key_exists('accounting_id', $value)) {
            $prepayment = new Prepayment();
            $prepayment->setPrepaymentID($value['accounting_id']);
            $payment->setPrepayment($prepayment);
        }
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|CreateContactResponse
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
     * @return CreatePaymentResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreatePaymentResponse($this, $data);
    }


}
