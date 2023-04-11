<?php

namespace PHPAccounting\Xero\Message\Invoices\Responses;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;

/**
 * Delete Invoice(s) Response
 * @package PHPAccounting\XERO\Message\Invoices\Responses
 */
class DeleteInvoiceResponse extends AbstractXeroResponse
{

    /**
     * Parse status
     * @param $data
     * @return string|null
     */
    private function parseStatus($data) {
        if ($data) {
            switch($data) {
                case 'DRAFT':
                case 'PAID':
                    return $data;
                case 'SUBMITTED':
                case 'AUTHORISED':
                    return 'OPEN';
                case 'VOIDED':
                    return 'DELETED';
            }
        }
        return null;
    }

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getInvoices(){
        $invoices = [];
        foreach ($this->data as $invoice) {
            $newInvoice = [];
            $newInvoice['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('InvoiceID', $invoice);
            $newInvoice['status'] = $this->parseStatus(IndexSanityCheckHelper::indexSanityCheck('Status', $invoice));
            $newInvoice['updated_at'] = IndexSanityCheckHelper::indexSanityCheck('UpdatedDateUTC', $invoice);
            array_push($invoices, $newInvoice);
        }

        return $invoices;
    }
}