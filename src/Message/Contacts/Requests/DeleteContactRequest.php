<?php

namespace PHPAccounting\Xero\Message\Contacts\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Contacts\Responses\DeleteContactResponse;
use PHPAccounting\Xero\Traits\AccountingIDRequestTrait;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Remote\Exception;;

/**
 * Delete Contact(s)
 * @package PHPAccounting\XERO\Message\Contacts\Requests
 */
class DeleteContactRequest extends AbstractXeroRequest
{
    use AccountingIDRequestTrait;

    public string $model = 'Contact';

    /**
     * Set Status Parameter from Parameter Bag
     * @see https://developer.xero.com/documentation/api/contacts
     * @param string $value Contact Name
     * @return DeleteContactRequest
     */
    public function setStatus($value) {
        return  $this->setParameter('status', $value);
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
        $this->issetParam('ContactID', 'accounting_id');
        $this->data['ContactStatus'] = 'ARCHIVED';
        return $this->data;
    }

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|DeleteContactResponse
     */
    public function sendData($data)
    {

        if($data instanceof InvalidRequestException) {
            $response = parent::handleRequestException($data, 'InvalidRequestException');
            return $this->createResponse($response);
        }

        try {
            $xero = $this->createXeroApplication();


            $contact = new Contact($xero);
            foreach ($data as $key => $value){
                $methodName = 'set'. $key;
                $contact->$methodName($value);
            }

            $response = $xero->save($contact);

        } catch(Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }

        return $this->createResponse($response->getElements());
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return DeleteContactResponse
     */
    public function createResponse($data)
    {
        return $this->response = new DeleteContactResponse($this, $data);
    }
}
