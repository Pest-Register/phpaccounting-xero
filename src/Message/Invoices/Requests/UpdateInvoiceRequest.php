<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 14/05/2019
 * Time: 12:46 PM
 */

namespace PHPAccounting\Xero\Message\Invoices\Requests;




use PHPAccounting\Xero\Message\AbstractRequest;

class UpdateInvoiceRequest extends AbstractRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        // TODO: Implement getData() method.
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        // TODO: Implement sendData() method.
    }
}