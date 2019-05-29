<?php

namespace PHPAccounting\Xero\Message\Invoices\Requests;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Contacts\Responses\GetContactResponse;
use PHPAccounting\Xero\Message\Invoices\Responses\GetInvoiceResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\Invoice;
use XeroPHP\Remote\Request;

class GetInvoiceRequest extends AbstractRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */

    /**
     * @param $value
     * @return GetInvoiceRequest
     */
    public function setAccountingIDs($value)
    {
        return $this->setParameter('accounting_ids', $value);
    }

    public function setPage($value)
    {
        return $this->setParameter('page', $value);
    }

    /**
     * Return comma delimited string of accounting IDs
     * @return mixed
     */
    public function getAccountingIDs()
    {
        return implode(', ', $this->getParameter('accounting_ids'));
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->getParameter('page');
    }

    /**
     * Send the request with specified data
     *
     * @param  mixed $data The data to send
     * @return GetContactResponse
     */

    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();
            $xero->getOAuthClient()->setToken($this->getAccessToken());
            $xero->getOAuthClient()->setTokenSecret($this->getAccessTokenSecret());

            if ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $invoices = $xero->loadByGUID(Invoice::class, $this->getAccountingIDs());
                }
                 else {
                     $invoices = $xero->loadByGUIDs(Invoice::class, $this->getAccountingIDs());
                 }
            } else {
                $invoices = $xero->load(Invoice::class)->execute();
            }
            $response = $invoices;

        } catch (\Exception $exception) {
            $response = [
                'status' => 'error',
                'detail' => $exception->getMessage()
            ];
        }
        return $this->createResponse($response);
    }

    public function createResponse($data)
    {
        return $this->response = new GetInvoiceResponse($this, $data);
    }
}