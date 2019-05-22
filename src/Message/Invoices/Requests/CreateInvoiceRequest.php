<?php

namespace PHPAccounting\Xero\Message\Invoices\Requests;

use PHPAccounting\Xero\Message\Invoices\Responses\CreateInvoiceResponse;
use AbstractRequest;

class CreateInvoiceRequest extends AbstractRequest
{
    public function getData()
    {
        $data = [];

        $this->issetParam($data, 'Type', 'type');
        $this->issetParam($data, 'Contact', 'client');
        $this->issetParam($data, 'LineItems', 'invoice_data');
        $this->issetParam($data, 'Date', 'invoice_date');
        $this->issetParam($data, 'DueDate', 'invoice_due_date');

        return $data;
    }

    public function sendData($data)
    {
        $response = parent::sendData($data);
        $this->createResponse($response->getData(), $response->getHeaders());
    }

    public function getEndpoint()
    {
        return $this->endpoint . '/Invoices';
    }

    public function createResponse($data, $headers = [])
    {
        return $this->response = new CreateInvoiceResponse($this, $data, $headers);
    }
}