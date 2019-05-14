<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 13/05/2019
 * Time: 4:24 PM
 */

namespace PHPAccounting\XERO\Message\Customers\Responses;


use PHPAccounting\Common\Message\RequestInterface;
use Response;

class CreateCustomerResponse extends Response
{

    public function __construct(RequestInterface $request, $data, array $headers = [])
    {
        parent::__construct($request, $data, $headers);
    }
}