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

    public function __construct(RequestInterface $request, $data, $headers = [])
    {
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
}