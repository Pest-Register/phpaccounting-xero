<?php

namespace PHPAccounting\Xero\Message\Contacts\Requests;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Contacts\Responses\GetContactResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Remote\Request;

/**
 * Get One or Multiple Contacts
 * @param array $parameters
 * @bodyParam array $parameters
 * @bodyParam parameters.page int optional Page Index for Pagination
 * @bodyParam parameters.accountingIDs array optional Array of GUIDs for Contact Retrieval / Filtration
 * @return \Omnipay\Common\Message\AbstractRequest
 */
class GetContactRequest extends AbstractRequest
{

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */

    /**
     * @param $value
     * @return GetContactRequest
     */
    public function setAccountingIDs($value) {
        return $this->setParameter('accounting_ids', $value);
    }

    public function setPage($value) {
        return $this->setParameter('page', $value);
    }

    /**
     * Return comma delimited string of accounting IDs
     * @return mixed
     */
    public function getAccountingIDs() {
        return implode(', ',$this->getParameter('accounting_ids'));
    }

    /**
     * @return mixed
     */
    public function getPage() {
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
                $contacts = $xero->loadByGUIDs(Contact::class, $this->getAccountingIDs());
            } else {
                $contacts = $xero->load(Contact::class)->execute();
            }
            $response = $contacts;

        } catch (\Exception $exception){
            $response = [
                'status' => 'error',
                'detail' => $exception->getMessage()
            ];
            return $this->createResponse($response);
        }
        return $this->createResponse($response);
    }

    public function createResponse($data)
    {
        return $this->response = new GetContactResponse($this, $data);
    }

}