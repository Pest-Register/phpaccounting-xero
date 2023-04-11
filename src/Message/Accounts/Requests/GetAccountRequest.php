<?php

namespace PHPAccounting\Xero\Message\Accounts\Requests;

use PHPAccounting\Xero\Helpers\SearchQueryBuilder as SearchBuilder;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Accounts\Responses\GetAccountResponse;
use PHPAccounting\Xero\Traits\GetRequestTrait;
use XeroPHP\Models\Accounting\Account;
use XeroPHP\Remote\Exception;

/**
 * Get Account(s)
 * @package PHPAccounting\XERO\Message\Accounts\Requests
 */
class GetAccountRequest extends AbstractXeroRequest
{
    use GetRequestTrait;

    public string $model = 'Account';

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return \Omnipay\Common\Message\ResponseInterface|GetAccountResponse
     * @throws Exception
     */
    public function sendData($data)
    {
        try {
            $xero = $this->createXeroApplication();
            if ($this->getAccountingID()) {
                $accounts = $xero->loadByGUID(Account::class, $this->getAccountingID());
            }
            elseif ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $accounts = $xero->loadByGUID(Account::class, $this->getAccountingIDs());
                }
                else {
                    $accounts = $xero->loadByGUIDs(Account::class, $this->getAccountingIDs());
                }
            } else {
                if($this->getSearchParams() || $this->getSearchFilters())
                {
                    $query = SearchBuilder::buildSearchQuery(
                        $xero,
                        Account::class,
                        $this->getSearchParams(),
                        $this->getExactSearchValue(),
                        $this->getSearchFilters(),
                        $this->getMatchAllFilters()
                    );
                    if ($this->getPage()) {
                        $accounts = $query->page($this->getPage())->execute();
                    } else {
                        $accounts = $query->execute();
                    }
                } else {
                    $accounts = $xero->load(Account::class)->execute();
                }
            }
            $response = $accounts;

        } catch(Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }
        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return GetAccountResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetAccountResponse($this, $data);
    }

    public function getData()
    {
        return;
    }
}
