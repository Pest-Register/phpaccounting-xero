<?php

namespace PHPAccounting\Xero\Foundation;

use PHPAccounting\Xero\Foundation\Contracts\RequestInterface;
use PHPAccounting\Xero\Foundation\Contracts\ResponseInterface;

/**
 * Lightweight response base — replaces Omnipay\Common\Message\AbstractResponse.
 */
abstract class AbstractResponse implements ResponseInterface
{
    protected RequestInterface $request;
    protected mixed $data;

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getData()
    {
        return $this->data;
    }

    abstract public function isSuccessful(): bool;
}
