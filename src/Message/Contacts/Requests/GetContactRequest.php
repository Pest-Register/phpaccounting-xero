<?php
namespace PHPAccounting\Xero\Message\Contacts\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Contacts\Responses\GetContactResponse;
use PHPAccounting\Xero\Traits\GetRequestTrait;
use XeroPHP\Models\Accounting\Contact;
use XeroPHP\Remote\Exception;

use PHPAccounting\Xero\Helpers\SearchQueryBuilder as SearchBuilder;
/**
 * Get Contact(s)
 * @package PHPAccounting\XERO\Message\Contacts\Requests
 */
class GetContactRequest extends AbstractXeroRequest
{
    use GetRequestTrait;

    public string $model = 'Contact';

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|GetContactResponse
     * @throws \XeroPHP\Exception
     */
    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            $response = parent::handleRequestException($data, 'InvalidRequestException');
            return $this->createResponse($response);
        }

        try {
            $xero = $this->createXeroApplication();

            if ($this->getAccountingID()) {
                $contacts = $xero->loadByGUID(Contact::class, $this->getAccountingID());
            }
            elseif ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $contacts = $xero->loadByGUID(Contact::class, $this->getAccountingIDs());
                } else {
                    $contacts = $xero->loadByGUIDs(Contact::class, $this->getAccountingIDs());
                }
            } else {
                if($this->getSearchParams() || $this->getSearchFilters())
                {
                    $query = SearchBuilder::buildSearchQuery(
                        $xero,
                        Contact::class,
                        $this->getSearchParams(),
                        $this->getExactSearchValue(),
                        $this->getSearchFilters(),
                        $this->getMatchAllFilters()
                    );
                    if ($this->getPage()) {
                        $contacts = $query->page($this->getPage())->execute();
                    } else {
                        $contacts = $query->execute();
                    }
                } else {
                    $contacts = $xero->load(Contact::class)->page($this->getPage())->execute();
                }
            }
            $response = $contacts;


        } catch(Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }
        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return GetContactResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetContactResponse($this, $data);
    }

    public function getData()
    {
        // TODO: Implement getData() method.
    }
}
