<?php

namespace PHPAccounting\Xero\Message\Payments\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;

/**
 * Create Invoice(s) Response
 * @package PHPAccounting\XERO\Message\Invoices\Responses
 */
class CreatePaymentResponse extends AbstractResponse
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
     * @return array
     */
    public function getErrorMessage(){
        if ($this->data) {
            if(array_key_exists('status', $this->data)){
                return ErrorResponseHelper::parseErrorResponse(
                    isset($this->data['detail']) ? $this->data['detail'] : null,
                    isset($this->data['type']) ? $this->data['type'] : null,
                    isset($this->data['status']) ? $this->data['status'] : null,
                    isset($this->data['error_code']) ? $this->data['error_code'] : null,
                    isset($this->data['status_code']) ? $this->data['status_code'] : null,
                    isset($this->data['detail']) ? $this->data['detail'] : null,
                    $this->data,
                    'Payment');
            }
            if (count($this->data) === 0) {
                return [
                    'message' => 'NULL Returned from API or End of Pagination',
                    'exception' => 'NULL Returned from API or End of Pagination',
                    'error_code' => null,
                    'status_code' => null,
                    'detail' => null
                ];
            }
        }
        return null;
    }

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
