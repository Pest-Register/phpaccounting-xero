<?php

namespace PHPAccounting\Xero\Message\Payments\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Models\Accounting\Payment;

/**
 * Get Invoice(s) Response
 * @package PHPAccounting\XERO\Message\Invoices\Responses
 */
class GetPaymentResponse extends AbstractResponse
{
    /**
     * Check Response for Error or Success
     * @return boolean
     */
    public function isSuccessful()
    {
        if ($this->data) {
            if(array_key_exists('status', $this->data)){
                return !$this->data['status'] == 'error';
            }
            if ($this->data instanceof \XeroPHP\Remote\Collection) {
                if (count($this->data) == 0) {
                    return false;
                }
            } elseif (is_array($this->data)) {
                if (count($this->data) == 0) {
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return string
     */
    public function getErrorMessage(){
        if ($this->data) {
            if(array_key_exists('status', $this->data)){
                return ErrorResponseHelper::parseErrorResponse($this->data['detail'], 'Payment');
            }
            if (count($this->data) === 0) {
                return 'NULL Returned from API or End of Pagination';
            }
        }
        return null;
    }

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

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getPayments(){
        $payments = [];
        if ($this->data instanceof Payment){
            $payment = $this->data;
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

            array_push($payments, $newPayment);

        } else {
            foreach ($this->data as $payment) {
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

                array_push($payments, $newPayment);
            }
        }


        return $payments;
    }
}