<?php

use PHPAccounting\Common\AbstractGateway;

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
        return 'MYOB';
    }

    /**
     * Customer Requests
     * @param array $parameters
     * @return \PhpAccounting\Common\Message\AbstractRequest
     */

    public function createCustomer(array $parameters = []){
        return $this->createRequest('\PHPAccounting\MYOB\Message\Customers\Requests\CreateCustomerRequest', $parameters);
    }

    public function updateCustomer(array $parameters = []){
        return $this->createRequest('\PHPAccounting\MYOB\Message\Customers\Requests\CreateCustomerRequest', $parameters);
    }

    public function getContact(array $parameters = []){
        return $this->createRequest('\PHPAccounting\XERO\Message\Customers\Requests\GetContactRequest', $parameters);
    }

    public function deleteContact(array $parameters = []){
        return $this->createRequest('\PHPAccounting\XERO\Message\Customers\Requests\DeleteContactRequest', $parameters);
    }

    public function createInvoice(array $parameters = []){
        return $this->createRequest('\PHPAccounting\XERO\Message\Invoices\Requests\CreateInvoiceRequest', $parameters);
    }

    public function updateInvoice(array $parameters = []){
        return $this->createRequest('\PHPAccounting\XERO\Message\Invoices\Requests\UpdateInvoiceRequest', $parameters);
    }

    public function getInvoice(array $parameters = []){
        return $this->createRequest('\PHPAccounting\XERO\Message\Invoices\Requests\GetInvoiceRequest', $parameters);
    }

    public function deleteInvoice(array $parameters = []){
        return $this->createRequest('\PhpAccounting\XERO\Message\Invoices\Requests\DeleteInvoiceRequest', $parameters);
    }
}