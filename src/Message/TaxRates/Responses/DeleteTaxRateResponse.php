<?php

namespace PHPAccounting\Xero\Message\TaxRates\Responses;

use PHPAccounting\Xero\Message\AbstractXeroResponse;

/**
 * Delete InventoryItem(s) Response
 * @package PHPAccounting\XERO\Message\InventoryItems\Responses
 */
class DeleteTaxRateResponse extends AbstractXeroResponse
{

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getTaxRates(){
        $items= [];

        return $items;
    }
}