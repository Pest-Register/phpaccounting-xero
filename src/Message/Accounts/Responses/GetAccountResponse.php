<?php

namespace PHPAccounting\Xero\Message\Accounts\Responses;

use Omnipay\Common\Message\AbstractResponse;
use PHPAccounting\Xero\Helpers\ErrorResponseHelper;
use XeroPHP\Models\Accounting\Account;
use XeroPHP\Models\Accounting\ContactGroup;

/**
 * Get ContactGroup(s) Response
 * @package PHPAccounting\XERO\Message\ContactGroups\Responses
 */
class GetAccountResponse extends AbstractResponse
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
                return ErrorResponseHelper::parseErrorResponse($this->data['detail'],$this->data['type'],$this->data, 'Account');
            }
            if (count($this->data) === 0) {
                return 'NULL Returned from API or End of Pagination';
            }
        }
        return null;
    }

    /**
     * Return all Contact Groups with Generic Schema Variable Assignment
     * @return array
     */
    public function getAccounts(){
        $accounts = [];
        if ($this->data instanceof Account){
            $account = $this->data;
            $newAccount = [];
            $newAccount['accounting_id'] = $account->getAccountID();
            $newAccount['code'] = $account->getCode();
            $newAccount['name'] = $account->getName();
            $newAccount['description'] = $account->getDescription();
            $newAccount['type'] = $account->getType();
            $newAccount['is_bank_account'] = ($account->getType() === 'BANK');
            $newAccount['enable_payments_to_account'] = $account->getEnablePaymentsToAccount();
            $newAccount['show_inexpense_claims'] = $account->getShowInexpenseClaims();
            $newAccount['tax_type'] = $account->getTaxType();
            $newAccount['bank_account_number'] = $account->getBankAccountNumber();
            $newAccount['bank_account_type'] = $account->getBankAccountType();
            $newAccount['currency_code'] = $account->getCurrencyCode();
            $newAccount['system_account'] = $account->getSystemAccount();
            $newAccount['updated_at'] = $account->getUpdatedDateUTC();
            array_push($accounts, $newAccount);
        }
        else {
            foreach ($this->data as $account) {
                $newAccount = [];
                $newAccount['accounting_id'] = $account->getAccountID();
                $newAccount['code'] = $account->getCode();
                $newAccount['name'] = $account->getName();
                $newAccount['description'] = $account->getDescription();
                $newAccount['type'] = $account->getType();
                $newAccount['is_bank_account'] = ($account->getType() === 'BANK');
                $newAccount['enable_payments_to_account'] = $account->getEnablePaymentsToAccount();
                $newAccount['show_inexpense_claims'] = $account->getShowInexpenseClaims();
                $newAccount['tax_type'] = $account->getTaxType();
                $newAccount['bank_account_number'] = $account->getBankAccountNumber();
                $newAccount['bank_account_type'] = $account->getBankAccountType();
                $newAccount['currency_code'] = $account->getCurrencyCode();
                $newAccount['system_account'] = $account->getSystemAccount();
                $newAccount['updated_at'] = $account->getUpdatedDateUTC();
                array_push($accounts, $newAccount);
            }
        }

        return $accounts;
    }
}