<?php

namespace PHPAccounting\Xero\Message\Accounts\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use PHPAccounting\Xero\Message\AbstractXeroResponse;
use XeroPHP\Models\Accounting\Account;
use XeroPHP\Models\Accounting\ContactGroup;

/**
 * Get Account(s) Response
 * @package PHPAccounting\XERO\Message\ContactGroups\Responses
 */
class GetAccountResponse extends AbstractXeroResponse
{
    /**
     * Parses objects returned from Xero and converts to an array
     * @param $account
     * @return array
     */
    private function parseData($account): array
    {
        $newAccount = [];
        $newAccount['accounting_id'] = $account->getAccountID();
        $newAccount['code'] = $account->getCode();
        $newAccount['name'] = $account->getName();
        $newAccount['description'] = $account->getDescription();
        $newAccount['type'] = $account->getType();
        $newAccount['is_bank_account'] = ($account->getType() === 'BANK');
        $newAccount['enable_payments_to_account'] = $account->getEnablePaymentsToAccount();
        $newAccount['show_inexpense_claims'] = $account->getShowInexpenseClaims();
        $newAccount['tax_type_id'] = $account->getTaxType();
        $newAccount['bank_account_number'] = $account->getBankAccountNumber();
        $newAccount['bank_account_type'] = $account->getBankAccountType();
        $newAccount['currency_code'] = $account->getCurrencyCode();
        $newAccount['system_account'] = $account->getSystemAccount();
        $newAccount['updated_at'] = $account->getUpdatedDateUTC();
        return $newAccount;
    }

    /**
     * Return all Contact Groups with Generic Schema Variable Assignment
     * @return array
     */
    public function getAccounts(){
        $accounts = [];
        if ($this->data instanceof Account){
            $newAccount = $this->parseData($this->data);
            array_push($accounts, $newAccount);
        }
        else {
            foreach ($this->data as $account) {
                $newAccount = $this->parseData($account);
                array_push($accounts, $newAccount);
            }
        }

        return $accounts;
    }
}