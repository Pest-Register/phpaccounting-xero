<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 14/05/2019
 * Time: 5:07 PM
 */

namespace PHPAccounting\XERO\Message\Contacts\Responses;


use PHPAccounting\Common\Message\AbstractResponse;
use PHPAccounting\Common\Message\RequestInterface;

class UpdateContactResponse extends AbstractResponse
{

    public function __construct(RequestInterface $request, $data, $headers = [])
    {
        parent::__construct($request, json_decode($data, true), $headers);
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