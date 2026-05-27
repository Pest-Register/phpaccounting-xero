<?php

namespace PHPAccounting\Xero\Foundation;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use PHPAccounting\Xero\Foundation\Contracts\RequestInterface;
use PHPAccounting\Xero\Foundation\Contracts\ResponseInterface;
use PHPAccounting\Xero\Foundation\Exceptions\InvalidRequestException;
use PHPAccounting\Xero\Foundation\Traits\ParametersTrait;

/**
 * Lightweight request base — replaces Omnipay\Common\Message\AbstractRequest.
 *
 * Defines the same surface the package's request subclasses (and downstream
 * callers in laravelaccountingsync) rely on: parameter bag, validate(),
 * send() that delegates to sendData(getData()), and a response slot.
 */
abstract class AbstractRequest implements RequestInterface
{
    use ParametersTrait;

    protected ?ClientInterface $httpClient = null;
    protected ?ResponseInterface $response = null;

    public function __construct(?ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new Client([
            'timeout' => 60,
            'http_errors' => false,
        ]);
    }

    public function setHttpClient(ClientInterface $httpClient): static
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    public function getHttpClient(): ClientInterface
    {
        if ($this->httpClient === null) {
            $this->httpClient = new Client([
                'timeout' => 60,
                'http_errors' => false,
            ]);
        }
        return $this->httpClient;
    }

    /**
     * Validate that the named parameters are present (not null / not empty
     * string). Throws InvalidRequestException on the first missing field.
     * Mirrors the historical Omnipay validate() signature so existing
     * request classes work unchanged.
     */
    protected function validate(string ...$args): void
    {
        foreach ($args as $key) {
            $value = $this->getParameter($key);
            if ($value === null || $value === '') {
                throw new InvalidRequestException("The $key parameter is required");
            }
        }
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function send()
    {
        $data = $this->getData();
        return $this->sendData($data);
    }

    abstract public function getData();

    abstract public function sendData($data);
}
