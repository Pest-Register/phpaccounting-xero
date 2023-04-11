<?php

namespace PHPAccounting\Xero\Message\Payments\Responses;

use PHPAccounting\Xero\Message\AbstractXeroResponse;
use XeroPHP\Models\Accounting\Payment;

/**
 * Get Invoice(s) Response
 * @package PHPAccounting\XERO\Message\Invoices\Responses
 */
class GetPaymentResponse extends AbstractXeroResponse
{

    /**
     * Add Invoice to Payment
     * @param $data Array of single Contact
     * @param array $payment Xero Invoice Object Mapping
     * @return mixed
     */
    private function parseInvoice($data, $payment) {
        if ($data) {
            $newInvoice = [];
            $newInvoice['accounting_id'] = $data->getInvoiceID();
            $newInvoice['type'] = $data->getType();
            $newInvoice['invoice_number'] = $data->getInvoiceNumber();
            $newInvoice['contact'] = [];
            $newInvoice['contact']['accounting_id'] = $data['Contact']->getContactID();
            $newInvoice['contact']['name'] = $data['Contact']->getName();
            $payment['invoice'] = $newInvoice;
        }

        return $payment;
    }

    /**
     * Add Account to Payment
     * @param $data Array of single Contact
     * @param array $payment Xero Invoice Object Mapping
     * @return mixed
     */
    private function parseAccount($data, $payment) {
        if ($data) {
            $newAccount = [];
            $newAccount['accounting_id'] = $data->getAccountID();
            $newAccount['code'] = $data->getCode();
            $payment['account'] = $newAccount;
        }

        return $payment;
    }

    private function parseData($payment) {
        $newPayment = [];
        $newPayment['accounting_id'] = $payment->getPaymentID();
        $newPayment['date'] = $payment->getDate();
        $newPayment['amount'] = $payment->getAmount();
        $newPayment['reference_id'] = $payment->getReference();
        $newPayment['currency_rate'] = $payment->getCurrencyRate() ?: 1.0;
        $newPayment['type'] = $payment->getPaymentType();
        $newPayment['status'] = $payment->getStatus();
        $newPayment['is_reconciled'] = $payment->getIsReconciled();
        $newPayment['updated_at'] = $payment->getUpdatedDateUTC();
        $newPayment = $this->parseAccount($payment->getAccount(), $newPayment);
        $newPayment = $this->parseInvoice($payment->getInvoice(), $newPayment);

        return $newPayment;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getPayments(){
        $payments = [];
        if ($this->data instanceof Payment){
            $newPayment = $this->parseData($this->data);
            array_push($payments, $newPayment);

        } else {
            foreach ($this->data as $payment) {
                $newPayment = $this->parseData($payment);
                array_push($payments, $newPayment);
            }
        }


        return $payments;
    }
}