<?php

namespace PHPAccounting\Xero\Message\Accounts\Requests\Traits;

trait AccountRequestTrait
{
    /**
     * Get Code Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getCode() {
        return $this->getParameter('code');
    }

    /**
     * Set Code Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Code
     */
    public function setCode($value){
        return $this->setParameter('code', $value);
    }

    /**
     * Get Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getName(){
        return $this->getParameter('name');
    }

    /**
     * Set Name Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Name
     */
    public function setName($value){
        return $this->setParameter('name', $value);
    }

    /**
     * Get Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getType(){
        return $this->getParameter('type');
    }

    /**
     * Set Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Type
     */
    public function setType($value){
        return $this->setParameter('type', $value);
    }

    /**
     * Get Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getStatus(){
        return $this->getParameter('status');
    }

    /**
     * Set Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Status
     */
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }

    /**
     * Get Description Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getDescription(){
        return $this->getParameter('description');
    }

    /**
     * Set Description Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Description
     */
    public function setDescription($value){
        return $this->setParameter('description', $value);
    }

    /**
     * Get Tax Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getTaxTypeID(){
        return $this->getParameter('tax_type_id');
    }

    /**
     * Set Tax Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Tax Type
     */
    public function setTaxTypeID($value){
        return $this->setParameter('tax_type_id', $value);
    }

    /**
     * Get Enable Payments to Account Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getEnablePaymentsToAccount(){
        return $this->getParameter('enable_payments_to_account');
    }

    /**
     * Set Tax Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Enable Payments to Account
     */
    public function setEnablePaymentsToAccount($value){
        return $this->setParameter('enable_payments_to_account', $value);
    }

    /**
     * Get Show Inexpense Claim Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getShowInexpenseClaims(){
        return $this->getParameter('show_inexpense_claims');
    }

    /**
     * Set Show Inexpense Claim Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Show Inexpense Claim
     */
    public function setShowInexpenseClaims($value){
        return $this->setParameter('show_inexpense_claims', $value);
    }

    /**
     * Set Bank Account Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Show Inexpense Claim
     */
    public function setBankAccountType($value){
        return $this->setParameter('bank_account_type', $value);
    }

    /**
     * Get Bank Account Type Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getBankAccountType(){
        return $this->getParameter('bank_account_type');
    }

    /**
     * Set Bank Account Number Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Show Inexpense Claim
     */
    public function setBankAccountNumber($value){
        return $this->setParameter('bank_account_number', $value);
    }

    /**
     * Get Bank Account Number Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getBankAccountNumber(){
        return $this->getParameter('bank_account_number');
    }

    /**
     * Set Currency Code Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param string $value Account Show Inexpense Claim
     */
    public function setCurrencyCode($value){
        return $this->setParameter('currency_code', $value);
    }

    /**
     * Get Bank Account Number Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getCurrencyCode(){
        return $this->getParameter('currency_code');
    }
}