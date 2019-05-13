<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 13/05/2019
 * Time: 4:36 PM
 */

namespace PHPAccounting\XERO\Message\Customers\Requests;


use AbstractRequest;

class UpdateContactRequest extends AbstractRequest
{

    public function getEndpoint()
    {
        return $this->endpoint . '/Contacts';
    }
}