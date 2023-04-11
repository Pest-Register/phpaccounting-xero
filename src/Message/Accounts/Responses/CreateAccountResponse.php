<?php

namespace PHPAccounting\Xero\Message\Accounts\Responses;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;

/**
 * Create Account(s) Response
 * @package PHPAccounting\XERO\Message\ContactGroups\Responses
 */
class CreateAccountResponse extends AbstractXeroResponse
{
    /**
     * Return all Invoices with Generic Schema Variable Assignment
     * @return array
     */
    public function getAccounts(){
        $accounts = [];
        foreach ($this->data as $account) {
            $newAccount = [];
            $newAccount['accounting_id'] = IndexSanityCheckHelper::indexSanityCheck('AccountID', $account);
            $newAccount['code'] = IndexSanityCheckHelper::indexSanityCheck('Code', $account);
            $newAccount['name'] = IndexSanityCheckHelper::indexSanityCheck('Name', $account);
            $newAccount['description'] = IndexSanityCheckHelper::indexSanityCheck('Description', $account);
            $newAccount['type'] = IndexSanityCheckHelper::indexSanityCheck('Type', $account);
            $newAccount['is_bank_account'] = (IndexSanityCheckHelper::indexSanityCheck('Type', $account) === 'BANK');
            $newAccount['enable_payments_to_account'] = IndexSanityCheckHelper::indexSanityCheck('EnablePaymentsToAccount', $account);;
            $newAccount['show_inexpense_claims'] = IndexSanityCheckHelper::indexSanityCheck('ShowInexpenseClaims', $account);
            $newAccount['tax_type_id'] = IndexSanityCheckHelper::indexSanityCheck('TaxType', $account);
            $newAccount['bank_account_number'] = IndexSanityCheckHelper::indexSanityCheck('BankAccountNumber', $account);
            $newAccount['bank_account_type'] = IndexSanityCheckHelper::indexSanityCheck('BankAccountType', $account);
            $newAccount['currency_code'] = IndexSanityCheckHelper::indexSanityCheck('CurrencyCode', $account);
            $newAccount['system_account'] = IndexSanityCheckHelper::indexSanityCheck('SystemAccount', $account);
            $newAccount['updated_at'] = IndexSanityCheckHelper::indexSanityCheck('UpdatedDateUTC', $account);
            array_push($accounts, $newAccount);
        }

        return $accounts;
    }
}