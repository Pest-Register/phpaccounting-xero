<?php

namespace PHPAccounting\Xero\Foundation\Contracts;

interface ResponseInterface
{
    public function getRequest(): RequestInterface;

    public function isSuccessful(): bool;

    public function getData();
}
