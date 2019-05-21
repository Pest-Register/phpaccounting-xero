<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 21/05/2019
 * Time: 3:05 PM
 */

namespace PHPAccounting\XERO\Message\Contacts\Responses;




use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class GetContactResponse extends AbstractResponse
{
    protected $headers;
    public function __construct(RequestInterface $request, $data, $headers = [])
    {
        $this->headers = $headers;
        parent::__construct($request, json_decode($data, true));
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->data != null;
    }

    public function getContacts(){
        return $this->data['Contacts'];
    }

    public function getHeaders(){
        return $this->headers;
    }
}