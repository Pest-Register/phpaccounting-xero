<?php

namespace PHPAccounting\XERO\Message\Invoices\Responses;


use PHPAccounting\Common\Message\RequestInterface;
use Response;

class CreateInvoiceResponse extends Response
{

    public function __construct(RequestInterface $request, $data, array $headers = [])
    {
        parent::__construct($request, $data, $headers);
    }
}