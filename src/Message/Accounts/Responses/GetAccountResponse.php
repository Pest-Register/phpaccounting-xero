<?php

namespace PHPAccounting\Xero\Message\Accounts\Responses;

use Omnipay\Common\Message\AbstractResponse;
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
        if(array_key_exists('status', $this->data)){
            return !$this->data['status'] == 'error';
        }
        return true;
    }

    /**
     * Fetch Error Message from Response
     * @return string
     */
    public function getErrorMessage(){
        if(array_key_exists('status', $this->data)){
            return $this->data['detail'];
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
            $newAccount['name'] = $account->getName();
            array_push($accounts, $newAccount);
        }
        else {
            foreach ($this->data as $account) {
                $newContactGroup = [];
                $newAccount = [];
                $newAccount['accounting_id'] = $account->getAccountID();
                $newAccount['name'] = $account->getName();
                array_push($accounts, $newAccount);
            }
        }

        return $accounts;
    }
}