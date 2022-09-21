<?php

namespace PHPAccounting\Xero\Message\Accounts\Responses;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;

/**
 * Delete Account(s) Response
 * @package PHPAccounting\XERO\Message\ContactGroups\Responses
 */
class DeleteAccountResponse extends AbstractXeroResponse
{

    /**
     * Return all Accounts with Generic Schema Variable Assignment
     * @return array
     */
    public function getAccounts(){
        $accounts = [];
        foreach ($this->data as $account) {
            $newAccount = [];
            $newAccount['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('AccountID', $account);
            $newAccount['status'] = IndexSanityCheckHelper::indexSanityCheck('Status', $account);
            $newAccount['updated_at'] = IndexSanityCheckHelper::indexSanityCheck('UpdatedDateUTC', $account);
            array_push($accounts, $newAccount);
        }

        return $accounts;
    }
}