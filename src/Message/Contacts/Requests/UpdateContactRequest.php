<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 13/05/2019
 * Time: 4:36 PM
 */

namespace PHPAccounting\XERO\Message\Customers\Requests;


use AbstractRequest;
use PHPAccounting\XERO\Message\Contacts\Responses\UpdateCustomerResponse;

class UpdateContactRequest extends AbstractRequest
{

    public function setContactReference($value){
        return $this->setParameter('contactReference', $value);
    }

    public function getContactReference(){
        return $this->getParameter('contactReference');
    }

    public function getData()
    {
        $this->validate('name');
        $data = [];

        $this->issetParam($data, 'Name', 'name');
        $this->issetParam($data, 'FirstName', 'first_name');
        $this->issetParam($data, 'LastName', 'first_name');
        $this->issetParam($data, 'ContactID', 'contact_id');
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


    public function createResponse($data, $headers = [])
    {
        return $this->response = new UpdateCustomerResponse($this, $data, $headers);
    }

    public function getEndpoint()
    {
        return $this->endpoint . '/Contacts/' . $this->getContactReference();
    }
}