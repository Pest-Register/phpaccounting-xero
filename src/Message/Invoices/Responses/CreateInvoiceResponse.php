<?php

namespace PHPAccounting\XERO\Message\Invoices\Responses;



use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class CreateInvoiceResponse extends AbstractResponse
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