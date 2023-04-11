<?php

namespace PHPAccounting\Xero\Message\Payments\Responses;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;

/**
 * Delete Invoice(s) Response
 * @package PHPAccounting\XERO\Message\Invoices\Responses
 */
class DeletePaymentResponse extends AbstractXeroResponse
{

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getPayments(){
        $payment = [];
        foreach ($this->data as $payment) {
            $newPayment = [];
            $newPayment['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('PaymentID', $payment);
            $newPayment['status'] = IndexSanityCheckHelper::indexSanityCheck('Status', $payment);
            $newPayment['updated_at'] = IndexSanityCheckHelper::indexSanityCheck('UpdatedDateUTC', $payment);
            array_push($payment, $newPayment);
        }

        return $payment;
    }
}