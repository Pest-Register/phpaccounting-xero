<?php

namespace PHPAccounting\Xero\Message\Payments\Requests\Traits;

use XeroPHP\Models\Accounting\Account;
use XeroPHP\Models\Accounting\CreditNote;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Overpayment;
use XeroPHP\Models\Accounting\Payment;
use XeroPHP\Models\Accounting\Prepayment;

trait PaymentRequestTrait
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
     */
    public function setDate($value){
        return $this->setParameter('date', $value);
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
}