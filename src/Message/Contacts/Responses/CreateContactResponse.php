<?php

namespace PHPAccounting\XERO\Message\Customers\Responses;

use PHPAccounting\Common\Message\AbstractResponse;
use PHPAccounting\Common\Message\RequestInterface;

class CreateContactResponse extends AbstractResponse
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