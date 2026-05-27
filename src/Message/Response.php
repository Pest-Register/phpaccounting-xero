<?php
namespace PHPAccounting\Xero\Message;

use PHPAccounting\Xero\Foundation\AbstractResponse;
use PHPAccounting\Xero\Foundation\Contracts\RequestInterface;

class Response extends AbstractResponse
{
    /**
     * Request id
     *
     * @var string URL
     */
    protected $requestId = null;

    /**
     * @var array
     */
    protected $headers = [];

    public function __construct(RequestInterface $request, $data, $headers = [])
    {
        $this->request = $request;
        $this->data = json_decode($data, true);
        $this->headers = $headers;
        parent::__construct($request, $data);
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful(): bool
    {
        return $this->data != null;
    }

    public function getHeaders(){
        return $this->headers;
    }
}