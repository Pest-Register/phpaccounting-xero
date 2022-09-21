<?php

namespace PHPAccounting\Xero\Message\ContactGroups\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\ContactGroups\Responses\DeleteContactGroupResponse;
use XeroPHP\Application;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\ContactGroup;
use XeroPHP\Remote\Exception;
use XeroPHP\Remote\Request;
use XeroPHP\Remote\URL;
use XeroPHP\Remote\Exception\UnauthorizedException;
use Calcinai\OAuth2\Client\Provider\Exception\XeroProviderException;
use XeroPHP\Remote\Exception\BadRequestException;
use XeroPHP\Remote\Exception\ForbiddenException;
use XeroPHP\Remote\Exception\ReportPermissionMissingException;
use XeroPHP\Remote\Exception\NotFoundException;
use XeroPHP\Remote\Exception\InternalErrorException;
use XeroPHP\Remote\Exception\NotImplementedException;
use XeroPHP\Remote\Exception\RateLimitExceededException;
use XeroPHP\Remote\Exception\NotAvailableException;
use XeroPHP\Remote\Exception\OrganisationOfflineException;
/**
 * Delete Contact Group(s)
 * @package PHPAccounting\XERO\Message\ContactGroups\Requests
 */
class DeleteContactGroupRequest extends AbstractXeroRequest
{
    public string $model = 'ContactGroup';

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
        } catch (\XeroPHP\Exception $exception) {
            $response = [
                'status' => 'error',
                'detail' => $exception->getMessage()
            ];

            return $response;
        }

        try {
            $request = new Request($xero, $url, Request::METHOD_DELETE);
            $request->send();
        } catch (\XeroPHP\Exception $exception) {
            $response = [
                'status' => 'error',
                'detail' => $exception->getMessage()
            ];

            return $response;
        }
    }

    /**
     * Delete Specific Contacts from Contact Group
     * @param ContactGroup $contactGroup Xero Contact Group Object
     * @param array $contacts Array of Contact IDs as strings
     * @param Application $xero Xero Endpoint Application Instance
     * @return array|\XeroPHP\Remote\Response
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
                } catch (\XeroPHP\Exception $exception) {
                    $response = [
                        'status' => 'error',
                        'detail' => $exception->getMessage()
                    ];

                    return $response;
                }

                try {
                    $request = new Request($xero, $url, Request::METHOD_DELETE);
                    $response = $request->send();
                } catch (\XeroPHP\Exception $exception) {
                    $response = [
                        'status' => 'error',
                        'detail' => $exception->getMessage()
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
        try {
            $this->validate('accounting_id');
        } catch (InvalidRequestException $exception) {
            return $exception;;
        }
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
        if($data instanceof InvalidRequestException) {
            $response = parent::handleRequestException($data, 'InvalidRequestException');
            return $this->createResponse($response);
        }
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

            $response = $xero->save($contactGroup);

        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
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
