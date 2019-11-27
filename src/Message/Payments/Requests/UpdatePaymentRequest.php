<?php

namespace PHPAccounting\Xero\Message\Payments\Requests;

use PHPAccounting\Xero\Helpers\IndexSanityInsertionHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Payments\Responses\UpdatePaymentResponse;
use XeroPHP\Models\Accounting\Account;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\CreditNote;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Invoice\LineItem;
use XeroPHP\Models\Accounting\Overpayment;
use XeroPHP\Models\Accounting\Payment;
use XeroPHP\Models\Accounting\Prepayment;

/**
 * Update Invoice(s)
 * @package PHPAccounting\XERO\Message\Invoices\Requests
 */
class UpdatePaymentRequest extends AbstractRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
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
     * @return UpdatePaymentRequest
     */
    public function setOverpayment($value){
        return $this->setParameter('overpayment', $value);
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
        $this->validate('account', 'amount', 'date');

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
     * @return UpdatePaymentResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();
            $xero->getOAuthClient()->setToken($this->getAccessToken());
            $xero->getOAuthClient()->setTokenSecret($this->getAccessTokenSecret());

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
                    $date = \DateTime::createFromFormat('Y-m-d', $value);
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
            $response = $payment->save();
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
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return UpdatePaymentResponse
     */
    public function createResponse($data)
    {
        return $this->response = new UpdatePaymentResponse($this, $data);
    }

}