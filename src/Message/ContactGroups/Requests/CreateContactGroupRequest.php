<?php

namespace PHPAccounting\Xero\Message\ContactGroups\Requests;


use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractRequest;
use PHPAccounting\Xero\Message\ContactGroups\Responses\CreateContactGroupResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\ContactGroup;

class CreateContactGroupRequest extends AbstractRequest
{
    public function setName($value){
        return $this->setParameter('name', $value);
    }

    public function setStatus($value){
        return $this->setParameter('status', $value);
    }

    public function getName() {
        return $this->getParameter('name');
    }

    public function getStatus() {
        return $this->getParameter('status');
    }

    public function setContacts($value) {
        return $this->setParameter('contacts', $value);
    }

    public function getContacts() {
        return $this->getParameter('contacts');
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

    private function addContactsToGroup($contactGroup, $contacts) {
        if ($contacts) {
            foreach($contacts as $contact) {
                $newContact = new Contact();
                $newContact->setContactID($contact['ContactID']);
                $contactGroup->addContact($newContact);
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
        $this->validate('name');
        $this->issetParam('Name', 'name');
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
                if ($key !== 'Contacts') {
                    $methodName = 'set'. $key;
                    $contactGroup->$methodName($value);
                }
            }

            $response = $contactGroup->save();

            if ($this->data['Contacts']) {
                $responseData = $response->getElements()[0];
                if (array_key_exists('ContactGroupID', $response->getElements()[0])) {
                    $contactGroup = new ContactGroup($xero);
                    $contactGroup->setContactGroupID($responseData['ContactGroupID']);
                    $this->addContactsToGroup($contactGroup, $this->data['Contacts']);
                    $response = $contactGroup->save();
                }
            }

        } catch (\Exception $exception){
            $response = [
                'status' => 'error',
                'detail' => $exception->getMessage()
            ];
        }

        return $this->createResponse($response->getElements());
    }

    public function createResponse($data)
    {
        return $this->response = new CreateContactGroupResponse($this, $data);
    }
}