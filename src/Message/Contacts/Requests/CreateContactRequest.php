<?php


/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 13/05/2019
 * Time: 3:24 PM
 */

class CreateContactRequest extends AbstractRequest
{


    public function getName(){
        return $this->getParameter('name');
    }

    public function setName($value){
        return $this->setParameter('name', $value);
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        $data = [];

        $data['Name'] = $this->getName();
        $data['family_name'] = $this->getLastName();
        $data['company_name'] = $this->getCompanyName();
        $data['email_address'] = $this->getEmail();

        return $data;
        // TODO: Implement getData() method.
    }

    public function getEndpoint()
    {
        return $this->endpoint . '/Contacts';
    }


}