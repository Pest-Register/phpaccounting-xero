<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 28/05/2019
 * Time: 11:18 AM
 */

namespace PHPAccounting\Xero\Message\ContactGroups\Requests;


use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\ContactGroups\Responses\DeleteContactGroupResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\ContactGroup;
use XeroPHP\Remote\Exception;
use XeroPHP\Remote\Request;
use XeroPHP\Remote\URL;

class DeleteContactGroupRequest extends AbstractRequest
{
    public function setStatus($value){
        return $this->setParameter('status', $value);
    }

    public function getStatus($value){
        return $this->setParameter('status', $value);
    }

    public function setContacts($value) {
        return $this->setParameter('contacts', $value);
    }

    public function getContacts() {
        return $this->getParameter('contacts');
    }

    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }


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
     * @param $contactGroup
     * @param $xero
     * @return array
     */
    private function deleteAllContactsFromGroup($contactGroup, $xero) {
        try {
            $url = new URL($xero, sprintf('%s/%s/%s/',
                    ContactGroup::getResourceURI(), $contactGroup->getGUID(),
                    Contact::getResourceURI())
            );
        } catch (Exception $exception) {
            $response = [
                'status' => 'error',
                'detail' => 'Exception when creating transaction: ', $exception->getMessage()
            ];

            return $response;
        }

        try {
            $request = new Request($xero, $url, Request::METHOD_DELETE);
            $request->send();
        } catch (Exception $exception) {
            $response = [
                'status' => 'error',
                'detail' => 'Exception when creating transaction: ', $exception->getMessage()
            ];

            return $response;
        }
    }

    /**
     * @param $contactGroup
     * @param $contacts
     * @param $xero
     * @return array
     */
    private function deleteContactsFromGroup($contactGroup, $contacts, $xero) {
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
                        'detail' => 'Exception when creating transaction: ', $exception->getMessage()
                    ];

                    return $response;
                }

                try {
                    $request = new Request($xero, $url, Request::METHOD_DELETE);
                    $request->send();
                } catch (Exception $exception) {
                    $response = [
                        'status' => 'error',
                        'detail' => $exception->getMessage()
                    ];

                    return $response;
                }
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
     * @param mixed $data
     * @return \Omnipay\Common\Message\ResponseInterface|CreateContactGroupResponse
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();
            $xero->getOAuthClient()->setToken($this->getAccessToken());
            $xero->getOAuthClient()->setTokenSecret($this->getAccessTokenSecret());

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
                'detail' => 'Exception when creating transaction: ', $exception->getMessage()
            ];
        }

        return $this->createResponse($response->getElements());
    }

    public function createResponse($data)
    {
        return $this->response = new DeleteContactGroupResponse($this, $data);
    }
}