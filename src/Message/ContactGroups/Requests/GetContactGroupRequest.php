<?php
namespace PHPAccounting\Xero\Message\ContactGroups\Requests;

use PHPAccounting\Xero\Helpers\SearchQueryBuilder as SearchBuilder;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\ContactGroups\Responses\GetContactGroupResponse;
use PHPAccounting\Xero\Message\Traits\GetRequestTrait;
use XeroPHP\Models\Accounting\ContactGroup;
use XeroPHP\Remote\Exception;

/**
 * Get Contact Group(s)
 * @package PHPAccounting\XERO\Message\ContactGroups\Requests
 */
class GetContactGroupRequest extends AbstractXeroRequest
{
    use GetRequestTrait;

    public string $model = 'ContactGroup';

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|GetContactGroupResponse
     * @throws \XeroPHP\Exception
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();

            if ($this->getAccountingID()) {
                $contactGroups = $xero->loadByGUID(ContactGroup::class, $this->getAccountingID());
            }
            elseif ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $contactGroups = $xero->loadByGUID(ContactGroup::class, $this->getAccountingIDs());
                }
                else {
                    $contactGroups = $xero->loadByGUIDs(ContactGroup::class, $this->getAccountingIDs());
                }
            } else {
                if($this->getSearchParams() || $this->getSearchFilters())
                {
                    $query = SearchBuilder::buildSearchQuery(
                        $xero,
                        ContactGroup::class,
                        $this->getSearchParams(),
                        $this->getExactSearchValue(),
                        $this->getSearchFilters(),
                        $this->getMatchAllFilters()
                    );
                    $contactGroups = $query->execute();
                } else {
                    $contactGroups = $xero->load(ContactGroup::class)->execute();
                }
            }
            $response = $contactGroups;

        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }
        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return GetContactGroupResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetContactGroupResponse($this, $data);
    }

    public function getData()
    {
        // TODO: Implement getData() method.
    }
}
