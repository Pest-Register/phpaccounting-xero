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
    const OAUTH_SIGNATURE_RSA_SHA1 = 'RSA-SHA1';
    const OAUTH_SIGNATURE_HMAC_SHA1 = 'HMAC-SHA1';
    const OAUTH_SIGNATURE_PLAINTEXT = 'PLAINTEXT';
    
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
    public function getConsumerKey() {

    }

    public function setConsumerKey($value) {

    }

    public function getConsumerSecret() {

    }

    public function setConsumerSecret() {

    }

    /**
     * Token Secret getters and setters
     * @return mixed
     */
    public function getTokenSecret() {

    }

    public function setTokenSecret($value) {

    }

    /**
     * Signature Methods getters and setters
     * @return mixed
     */
    public function getSignatureMethod() {

    }

    public function setSignatureMethod($value) {

    }

    public function getSignature() {

    }

    public function setSignature($value) {

    }

    public function getSignatureSecret() {

    }

    public function setSignatureSecret($value) {

    }

    public function getSignatureBaseString() {

    }

    public function setSignatureBaseString($value) {

    }

    /**
     * OAuth Verifier and Params getters and setters
     * @return mixed
     */
    public function getOauthVerifier() {

    }

    public function setOauthVerifier($value) {

    }

    public function getOauthParameters() {

    }

    public function setOauthParameters($value) {

    }

    /** Callback getters and setters
     * @return mixed
     */
    public function getCallbackURL() {

    }

    public function setCallbackURL($value) {

    }

    public function getNonce() {

    }

    public function setNonce($value) {

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

    public function getContact(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Contacts\Requests\GetContactRequest', $parameters);
    }

    public function deleteContact(array $parameters = []){
        return $this->createRequest('\PHPAccounting\Xero\Message\Contacts\Requests\DeleteContactRequest', $parameters);
    }

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
}