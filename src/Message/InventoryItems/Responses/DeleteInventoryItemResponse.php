<?php

namespace PHPAccounting\Xero\Message\InventoryItems\Responses;

use PHPAccounting\Xero\Message\AbstractXeroResponse;

/**
 * Delete InventoryItem(s) Response
 * @package PHPAccounting\XERO\Message\InventoryItems\Responses
 */
class DeleteInventoryItemResponse extends AbstractXeroResponse
{

    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getInventoryItems(){
        return [];
    }
}