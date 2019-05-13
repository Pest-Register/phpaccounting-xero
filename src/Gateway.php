<?php

use PhpAccounting\Common\AbstractGateway;

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

    public function CreateCustomer(array $parameters = []){
        return $this->createRequest('\PHPAccounting\MYOB\Message\Customers\Requests\CreateCustomerRequest', $parameters);
    }

    public function UpdateCustomer(array $parameters = []){
        return $this->createRequest('\PHPAccounting\MYOB\Message\Customers\Requests\CreateCustomerRequest', $parameters);
    }

    public function GetCustomer(array $parameters = []){

        if(array_key_exists('company', $parameters)) {
            return $this->createRequest('\PHPAccounting\MYOB\Message\Customers\Requests\GetCompanyCustomerRequest', $parameters);
        }
        else {
            return $this->createRequest('\PHPAccounting\MYOB\Message\Customers\Requests\GetCustomerRequest', $parameters);
        }
    }



    public function __call($name, $arguments)
    {
        // TODO: Implement @method \PhpAccounting\Common\Message\NotificationInterface acceptNotification(array $options = array())
        // TODO: Implement @method \PhpAccounting\Common\Message\RequestInterface authorize(array $options = array())
        // TODO: Implement @method \PhpAccounting\Common\Message\RequestInterface completeAuthorize(array $options = array())
        // TODO: Implement @method \PhpAccounting\Common\Message\RequestInterface capture(array $options = array())
        // TODO: Implement @method \PhpAccounting\Common\Message\RequestInterface purchase(array $options = array())
        // TODO: Implement @method \PhpAccounting\Common\Message\RequestInterface completePurchase(array $options = array())
        // TODO: Implement @method \PhpAccounting\Common\Message\RequestInterface refund(array $options = array())
        // TODO: Implement @method \PhpAccounting\Common\Message\RequestInterface fetchTransaction(array $options = [])
        // TODO: Implement @method \PhpAccounting\Common\Message\RequestInterface void(array $options = array())
        // TODO: Implement @method \PhpAccounting\Common\Message\RequestInterface createCard(array $options = array())
        // TODO: Implement @method \PhpAccounting\Common\Message\RequestInterface updateCard(array $options = array())
        // TODO: Implement @method \PhpAccounting\Common\Message\RequestInterface deleteCard(array $options = array())
    }
}