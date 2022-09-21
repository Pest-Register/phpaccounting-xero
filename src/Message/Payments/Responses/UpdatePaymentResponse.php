<?php

namespace PHPAccounting\Xero\Message\Payments\Responses;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;

/**
 * Update Invoice(s) Response
 * @package PHPAccounting\XERO\Message\Invoices\Responses
 */
class UpdatePaymentResponse extends AbstractXeroResponse
{

    /**
     * Add Invoice to Payment
     * @param $data Array of single Payment
     * @param array $payment Xero Payment Object Mapping
     * @return mixed
     */
    private function parseInvoice($data, $payment) {
        if ($data) {
            $newInvoice = [];
            $newInvoice['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('AccountID',$data);
            $newInvoice['type'] = IndexSanityCheckHelper::indexSanityCheck('Type',$data);
            $newInvoice['invoice_number'] = IndexSanityCheckHelper::indexSanityCheck('InvoiceNumber', $data);
            if (IndexSanityCheckHelper::indexSanityCheck('Contact', $data)) {
                $newInvoice['contact'] = [];
                $newInvoice['contact']['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('ContactID', $data['Contact']);
                $newInvoice['contact']['name'] = IndexSanityCheckHelper::indexSanityCheck('Name', $data['Contact']);
            }
            $payment['invoice'] = $newInvoice;
        }

        return $payment;
    }

    /**
     * Add Account to Payment
     * @param $data Array of single Payment
     * @param array $payment Xero Payment Object Mapping
     * @return mixed
     */
    private function parseAccount($data, $payment) {
        if ($data) {
            $newAccount = [];
            $newAccount['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('AccountID',$data);
            $newAccount['code'] = IndexSanityCheckHelper::indexSanityCheck('Code',$data);
            $payment['account'] = $newAccount;
        }

        return $payment;
    }

    /**
     * Return all Payments with Generic Schema Variable Assignment
     * @return array
     */
    public function getPayments(){
        $payments = [];
        foreach ($this->data as $payment) {
            $newPayment = [];
            $newPayment['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('PaymentID', $payment);
            $newPayment['date'] = IndexSanityCheckHelper::indexSanityCheck('Date', $payment);
            $newPayment['bank_amount'] = IndexSanityCheckHelper::indexSanityCheck('BankAmount', $payment);
            $newPayment['amount'] = IndexSanityCheckHelper::indexSanityCheck('Amount', $payment);
            $newPayment['reference_id'] = IndexSanityCheckHelper::indexSanityCheck('Reference', $payment);
            $newPayment['currency_rate'] = IndexSanityCheckHelper::indexSanityCheck('CurrencyRate', $payment) ?: 1.0;
            $newPayment['type'] = IndexSanityCheckHelper::indexSanityCheck('PaymentType', $payment);
            $newPayment['status'] = IndexSanityCheckHelper::indexSanityCheck('Status', $payment);
            $newPayment['has_account'] = IndexSanityCheckHelper::indexSanityCheck('HasAccount', $payment);
            $newPayment['is_reconciled'] = IndexSanityCheckHelper::indexSanityCheck('IsReconciled', $payment);
            $newPayment['updated_at'] = IndexSanityCheckHelper::indexSanityCheck('UpdatedDateUTC', $payment);
            if (IndexSanityCheckHelper::indexSanityCheck('Account', $payment)) {
                $newPayment = $this->parseAccount($payment['Account'], $newPayment);
            }
            if (IndexSanityCheckHelper::indexSanityCheck('Invoice', $payment)) {
                $newPayment = $this->parseInvoice($payment['Invoice'], $newPayment);
            }

            array_push($payments, $newPayment);
        }

        return $payments;
    }
}