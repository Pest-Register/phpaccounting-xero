<?php

namespace PHPAccounting\Xero\Message\ContactGroups\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Helpers\IndexSanityCheckHelper;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\ContactGroups\Requests\Traits\ContactGroupRequestTrait;
use PHPAccounting\Xero\Message\ContactGroups\Responses\CreateContactGroupResponse;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Models\Accounting\ContactGroup;
use XeroPHP\Remote\Exception;

/**
 * Create Contact Group(s)
 * @package PHPAccounting\XERO\Message\ContactGroups\Requests
 */
class CreateContactGroupRequest extends AbstractXeroRequest
{
    use ContactGroupRequestTrait;

    public string $model = 'ContactGroup';

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
            $this->validate('name');
        } catch (InvalidRequestException $exception) {
            return $exception;;
        }
        $this->issetParam('Name', 'name');
        $this->issetParam('Status', 'status');
        $this->data['Contacts'] = ($this->getContacts() != null ? $this->getContactData($this->getContacts()) : null);
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|CreateContactGroupResponse
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
                if ($key !== 'Contacts') {
                    $methodName = 'set'. $key;
                    $contactGroup->$methodName($value);
                }
            }

            $response = $contactGroup->save();

            if ($this->data['Contacts']) {
                $responseData = $response->getElements()[0];
                if (is_array($responseData)) {
                    if (array_key_exists('ContactGroupID', $response->getElements()[0])) {
                        $contactGroupID = $responseData['ContactGroupID'];
                    }
                } elseif (is_object($responseData)) {
                    if (property_exists($response->getElements()[0],'ContactGroupID')) {
                        $contactGroupID = $responseData->ContactGroupID;
                    }
                }
                $contactGroup = new ContactGroup($xero);
                $contactGroup->setContactGroupID($contactGroupID);
                $this->addContactsToGroup($contactGroup, $this->data['Contacts']);
                $response = $xero->save($contactGroup);
            }

        } catch(Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }

        return $this->createResponse($response->getElements());
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return CreateContactGroupResponse
     */
    public function createResponse($data)
    {
        return $this->response = new CreateContactGroupResponse($this, $data);
    }
}
