<?php

namespace PHPAccounting\Xero\Foundation\Contracts;

interface RequestInterface
{
    public function getData();

    public function send();

    public function getParameters(): array;

    public function getParameter(string $key);

    public function setParameter(string $key, $value);
}
