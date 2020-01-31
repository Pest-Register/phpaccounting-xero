<?php

namespace PHPAccounting\Xero\Message\ContactGroups\Requests;

use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\ContactGroups\Responses\DeleteContactGroupResponse;
use XeroPHP\Application;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\ContactGroup;
use XeroPHP\Remote\Exception;
use XeroPHP\Remote\Request;
use XeroPHP\Remote\URL;

/**
 * Delete Contact Group(s)
 * @package PHPAccounting\XERO\Message\ContactGroups\Requests
 */
class DeleteContactGroupRequest extends AbstractRequest
{
    /**
     * Set Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @param string $value Contact Name
     * @return DeleteContactGroupRequest
     */
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }

    /**
     * Get Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @return mixed
     */
    public function getStatus($value){
        return $this->setParameter('status', $value);
    }

    /**
     * Set Contacts Array from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @param $value
     * @return DeleteContactGroupRequest
     */
    public function setContacts($value) {
        return $this->setParameter('contacts', $value);
    }

    /**
     * Get Contacts Array from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @return mixed
     */
    public function getContacts() {
        return $this->getParameter('contacts');
    }

    /**
     * Set AccountingID from Parameter Bag (ContactGroupID generic interface)
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @param $value
     * @return DeleteContactGroupRequest
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (ContactGroupID generic interface)
     * @see https://developer.xero.com/documentation/api/contactgroups
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }

    /**
     * Get Contact Array with Contact ID References
     * @access public
     * @param array $data Array of Xero Contacts
     * @return array
     */
    private function getContactData($data) {
        $contacts = [];
        foreach($data as $contact) {
            $newContact = new Contact();
            $newContact->setContactID(IndexSanityCheckHelper::indexSanityCheck('accounting_id', $contact));
            array_push($contacts, $newContact);
        }

        return $contacts;
    }

    /**
     * Delete All Contacts from Contact Group
     * @param ContactGroup $contactGroup Xero Contact Group Object
     * @param Application $xero Xero Endpoint Application Instance
     * @return array
     */
    private function deleteAllContactsFromGroup(ContactGroup $contactGroup, $xero) {
        try {
            $url = new URL($xero, sprintf('%s/%s/%s/',
                    ContactGroup::getResourceURI(), $contactGroup->getGUID(),
                    Contact::getResourceURI())
            );
        } catch (Exception $exception) {
            $response = [
                'status' => 'error',
                json_decode(print_r($exception->getResponse()->getBody()->getContents(), true))->detail
            ];

            return $response;
        }

        try {
            $request = new Request($xero, $url, Request::METHOD_DELETE);
            $request->send();
        } catch (Exception $exception) {
            $response = [
                'status' => 'error',
                json_decode(print_r($exception->getResponse()->getBody()->getContents(), true))->detail
            ];

            return $response;
        }
    }

    /**
     * Delete Specific Contacts from Contact Group
     * @param ContactGroup $contactGroup Xero Contact Group Object
     * @param array $contacts Array of Contact IDs as strings
     * @param Application $xero Xero Endpoint Application Instance
     */
    private function deleteContactsFromGroup(ContactGroup $contactGroup, $contacts, $xero) {
        if ($contacts) {
            foreach($contacts as $contact) {
                $newContact = new Contact();
                $newContact->setContactID($contact['ContactID']);

                try {
                    $url = new URL($xero, sprintf('%s/%s/%s/%s',
                            ContactGroup::getResourceURI(), $contactGroup->getGUID(),
                            Contact::getResourceURI(), $contact->getGUID())
                    );
                } catch (Exception $exception) {
                    $response = [
                        'status' => 'error',
                        json_decode(print_r($exception->getResponse()->getBody()->getContents(), true))->detail
                    ];

                    return $response;
                }

                try {
                    $request = new Request($xero, $url, Request::METHOD_DELETE);
                    $response = $request->send();
                } catch (Exception $exception) {
                    $response = [
                        'status' => 'error',
                        json_decode(print_r($exception->getResponse()->getBody()->getContents(), true))->detail
                    ];

                    return $response;
                }

                return $response;
            }
        }
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('accounting_id');
        $this->issetParam('ContactGroupID', 'accounting_id');
        $this->issetParam('Status', 'status');
        $this->data['Contacts'] = ($this->getContacts() != null ? $this->getContactData($this->getContacts()) : null);
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|DeleteContactGroupResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();


            $contactGroup = new ContactGroup($xero);
            foreach ($data as $key => $value){
                if ($key === 'Contacts') {
                    $this->deleteContactsFromGroup($contactGroup, $value, $xero);
                } elseif ($key === 'status') {
                    if ($value === 'DELETED') {
                        // Delete all contacts if they exist
                        $this->deleteAllContactsFromGroup($contactGroup, $xero);
                    }
                    $methodName = 'set'. $key;
                    $contactGroup->$methodName($value);
                } else {
                    $methodName = 'set'. $key;
                    $contactGroup->$methodName($value);
                }
            }

            $response = $contactGroup->save();

        } catch (\Exception $exception){
            $response = [
                'status' => 'error',
                'detail' =>  json_decode(print_r($exception->getResponse()->getBody()->getContents(), true))->detail
            ];
            return $this->createResponse($response);
        }

        return $this->createResponse($response->getElements());
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return DeleteContactGroupResponse
     */
    public function createResponse($data)
    {
        return $this->response = new DeleteContactGroupResponse($this, $data);
    }
}