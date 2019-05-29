<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 14/05/2019
 * Time: 5:07 PM
 */

namespace PHPAccounting\Xero\Message\Contacts\Responses;



use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class UpdateContactResponse extends AbstractResponse
{

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        if(array_key_exists('status', $this->data)){
            return !$this->data['status'] == 'error';
        }
        return true;
    }

    public function getErrorMessage(){
        if(array_key_exists('status', $this->data)){
            return $this->data['detail'];
        }
        return null;
    }
}