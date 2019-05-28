<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 28/05/2019
 * Time: 11:19 AM
 */

namespace PHPAccounting\XERO\Message\ContactGroups\Requests;


use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\ContactGroups\Responses\GetContactGroupResponse;
use XeroPHP\Models\Accounting\ContactGroup;

class GetContactGroupRequest extends AbstractRequest
{
    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */

    /**
     * @param $value
     * @return GetContactGroupRequest
     */
    public function setAccountingIDs($value) {
        return $this->setParameter('accountingIDs', $value);
    }

    public function setPage($value) {
        return $this->setParameter('page', $value);
    }

    /**
     * Return comma delimited string of accounting IDs
     * @return mixed
     */
    public function getAccountingIDs() {
        return  implode(', ',$this->getParameter('accountingIDs'));
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
     * @return GetContactGroupResponse
     */

    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();
            $xero->getOAuthClient()->setToken($this->getAccessToken());
            $xero->getOAuthClient()->setTokenSecret($this->getAccessTokenSecret());

            if ($this->getAccountingIDs()) {
                $contacts = $xero->loadByGUIDs(ContactGroup::class, $this->getAccountingIDs());
            } else {
                $contacts = $xero->load(ContactGroup::class)->execute();
            }
            $response = $contacts;

        } catch (\Exception $exception){
            $response = [
                'status' => 'error',
                'detail' => 'Exception when creating transaction: ', $exception->getMessage()
            ];
        }
        return $this->createResponse($response);
    }

    public function createResponse($data)
    {
        return $this->response = new GetContactGroupResponse($this, $data);
    }
}