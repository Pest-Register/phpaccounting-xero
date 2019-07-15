<?php
namespace PHPAccounting\Xero;

use Omnipay\Common\AbstractGateway;

/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 13/05/2019
 * Time: 3:11 PM
 * @method \PhpAccounting\Common\Message\NotificationInterface acceptNotification(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface authorize(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface capture(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface purchase(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface completePurchase(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface refund(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface fetchTransaction(array $options = [])
 * @method \PhpAccounting\Common\Message\RequestInterface void(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface createCard(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface updateCard(array $options = array())
 * @method \PhpAccounting\Common\Message\RequestInterface deleteCard(array $options = array())
 */

class Gateway extends AbstractGateway
{
    /**
     * Get gateway display name
     *
     * This can be used by carts to get the display name for each gateway.
     * @return string
     */
    public function getName()
    {
        return 'Xero';
    }

    /**
     * Access Token getters and setters
     * @return mixed
     */

    public function getAccessToken()
    {
        return $this->getParameter('accessToken');
    }

    public function setAccessToken($value)
    {
        return $this->setParameter('accessToken', $value);
    }

    /**
     * Consumer Key getters and setters
     * @return mixed
     */

    public function setXeroConfig($value){
        return $this->setParameter('xeroConfig', $value);
    }

    /**
     * Token Secret getters and setters
     * @return mixed
     */

    public function setAccessTokenSecret($value)
    {
        return $this->setParameter('accessTokenSecret', $value);
    }

    public function getAccessTokenSecret() {
        return $this->getParameter('accessTokenSecret');
    }


    /**
     * Customer Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function createContact(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Contacts\Requests\CreateContactRequest', $parameters);
    }

    public function updateContact(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Contacts\Requests\UpdateContactRequest', $parameters);
    }

    /**
     * Get One or Multiple Contacts
     * @param array $parameters
     * @bodyParam array $parameters
     * @bodyParam parameters.page int optional Page Index for Pagination
     * @bodyParam parameters.accountingIDs array optional Array of GUIDs for Contact Retrieval / Filtration
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function getContact(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Contacts\Requests\GetContactRequest', $parameters);
    }

    public function deleteContact(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Contacts\Requests\DeleteContactRequest', $parameters);
    }


    /**
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function createContactGroup(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\ContactGroups\Requests\CreateContactGroupRequest', $parameters);
    }

    public function updateContactGroup(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\ContactGroups\Requests\UpdateContactGroupRequest', $parameters);
    }

    public function getContactGroup(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\ContactGroups\Requests\GetContactGroupRequest', $parameters);
    }

    public function deleteContactGroup(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\ContactGroups\Requests\DeleteContactGroupRequest', $parameters);
    }


    /**
     * Invoice Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function createInvoice(array $parameters = []){
            return $this->createRequest('\PHPAccounting\Xero\Message\Invoices\Requests\CreateInvoiceRequest', $parameters);
    }

    public function updateInvoice(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Invoices\Requests\UpdateInvoiceRequest', $parameters);
    }

    public function getInvoice(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Invoices\Requests\GetInvoiceRequest', $parameters);
    }

    public function deleteInvoice(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Invoices\Requests\DeleteInvoiceRequest', $parameters);
    }

    /**
     * Account Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function createAccount(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Accounts\Requests\CreateAccountRequest', $parameters);
    }

    public function updateAccount(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Accounts\Requests\UpdateAccountRequest', $parameters);
    }

    public function getAccount(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Accounts\Requests\GetAccountRequest', $parameters);
    }

    public function deleteAccount(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Accounts\Requests\DeleteAccountRequest', $parameters);
    }

    /**
     * Payment Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function createPayment(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Payments\Requests\CreatePaymentRequest', $parameters);
    }

    public function updatePayment(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Payments\Requests\UpdatePaymentRequest', $parameters);
    }

    public function getPayment(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Payments\Requests\GetPaymentRequest', $parameters);
    }

    public function deletePayment(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Payments\Requests\DeletePaymentRequest', $parameters);
    }

    /**
     * Organisation Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function getOrganisation(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Organisations\Requests\GetOrganisationRequest', $parameters);
    }

    /**
     * Inventory Item Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function createInventoryItem(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\InventoryItems\Requests\CreateInventoryItemRequest', $parameters);
    }

    public function updateInventoryItem(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\InventoryItems\Requests\UpdateInventoryItemRequest', $parameters);
    }

    public function getInventoryItem(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\InventoryItems\Requests\GetInventoryItemRequest', $parameters);
    }

    public function deleteInventoryItem(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\InventoryItems\Requests\DeleteInventoryItemRequest', $parameters);
    }

    /**
     * Tax Rates Requests
     * @param array $parameters
     * @return \Omnipay\Common\Message\AbstractRequest
     */

    public function createTaxRate(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\TaxRates\Requests\CreateTaxRateRequest', $parameters);
    }

    public function updateTaxRate(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\TaxRates\Requests\UpdateTaxRateRequest', $parameters);
    }

    public function getTaxRate(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\TaxRates\Requests\GetTaxRateRequest', $parameters);
    }

    public function deleteTaxRate(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\TaxRates\Requests\DeleteTaxRateRequest', $parameters);
    }

}