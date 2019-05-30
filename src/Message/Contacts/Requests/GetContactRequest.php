<?php
namespace PHPAccounting\Xero\Message\Contacts\Requests;

use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\Contacts\Responses\GetContactResponse;
use XeroPHP\Models\Accounting\Contact;

/**
 * Get Contact(s)
 * @package PHPAccounting\XERO\Message\Contacts\Requests
 */
class GetContactRequest extends AbstractRequest
{

    /**
     * Set AccountingID from Parameter Bag (ContactID generic interface)
     * @see https://developer.xero.com/documentation/api/contacts
     * @param $value
     * @return GetContactRequest
     */
    public function setAccountingIDs($value) {
        return $this->setParameter('accounting_ids', $value);
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param $value
     * @return GetContactRequest
     */
    public function setPage($value) {
        return $this->setParameter('page', $value);
    }

    /**
     * Return Comma Delimited String of Accounting IDs (ContactGroupIDs)
     * @return mixed comma-delimited-string
     */
    public function getAccountingIDs() {
        return implode(', ',$this->getParameter('accounting_ids'));
    }

    /**
     * Return Page Value for Pagination
     * @return integer
     */
    public function getPage() {
        return $this->getParameter('page');
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|GetContactResponse
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

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return GetContactResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetContactResponse($this, $data);
    }

}