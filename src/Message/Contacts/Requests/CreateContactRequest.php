<?php

use PHPAccounting\XERO\Message\Customers\Responses\CreateCustomerResponse;

class CreateContactRequest extends AbstractRequest
{


    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     * @throws \PHPAccounting\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('name');
        $data = [];

        $this->issetParam($data, 'Name', 'name');
        $this->issetParam($data, 'FirstName', 'first_name');
        $this->issetParam($data, 'LastName', 'first_name');
        $this->issetParam($data, 'ContactNumber', 'contact_number');
        $this->issetParam($data, 'ContactStatus', 'contact_status');
        $this->issetParam($data, 'EmailAddress', 'email_address');
        $this->issetParam($data, 'SkypeUserName', 'skype_user_name');
        $this->issetParam($data, 'ContactPersons', 'contact_persons');
        $this->issetParam($data, 'BankAccountDetails', 'bank_account_details');
        $this->issetParam($data, 'TaxNumber', 'tax_number');
        $this->issetParam($data, 'AccountsReceivableTaxType', 'accounts_receivable_tax_type');
        $this->issetParam($data, 'AccountsPayableTaxType', 'accounts_payable_tax_type');
        $this->issetParam($data, 'Addresses', 'addresses');
        $this->issetParam($data, 'Phones', 'phones');
        $this->issetParam($data, 'IsSupplier', 'is_supplier');
        $this->issetParam($data, 'IsCustomer', 'is_customer');
        $this->issetParam($data, 'DefaultCurrency', 'default_currency');
        $this->issetParam($data, 'XeroNetworkKey', 'xero_network_key');
        $this->issetParam($data, 'SalesDefaultAccountCode', 'sales_default_account_code');
        $this->issetParam($data, 'PurchasesDefaultAccountCode', 'purchase_default_account_code');
        $this->issetParam($data, 'SalesTrackingCategories', 'sales_tracking_categories');
        $this->issetParam($data, 'PurchasesTrackingCategories', 'purchase_tracking_categories');
        $this->issetParam($data, 'TrackingCategoryName', 'tracking_category_name');
        $this->issetParam($data, 'TrackingCategoryOption', 'tracking_category_option');
        $this->issetParam($data, 'PaymentTerms', 'payment_terms');


        return $data;
    }



    public function sendData($data)
    {
        $response = parent::sendData($data);
        $this->createResponse($response->getData(), $response->getHeaders());
    }

    public function getEndpoint()
    {
        return $this->endpoint . '/Contacts';
    }

    public function createResponse($data, $headers = [])
    {
        return $this->response = new CreateCustomerResponse($this, $data, $headers);
    }


}