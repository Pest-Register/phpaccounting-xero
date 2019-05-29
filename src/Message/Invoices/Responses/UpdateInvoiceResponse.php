<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 29/05/2019
 * Time: 1:07 PM
 */

namespace PHPAccounting\XERO\Message\Invoices\Responses;


use Omnipay\Common\Message\AbstractResponse;

class UpdateInvoiceResponse extends AbstractResponse
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