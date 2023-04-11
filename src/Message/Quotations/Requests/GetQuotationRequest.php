<?php


namespace PHPAccounting\Xero\Message\Quotations\Requests;

use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Helpers\SearchQueryBuilder as SearchBuilder;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\Quotations\Responses\GetQuotationResponse;
use PHPAccounting\Xero\Traits\GetRequestTrait;
use XeroPHP\Models\Accounting\Quote;
use XeroPHP\Remote\Exception;

/**
 * Get Quotation(s)
 * @package PHPAccounting\Xero\Message\Quotations\Requests
 */
class GetQuotationRequest extends AbstractXeroRequest
{
    use GetRequestTrait;

    public string $model = 'Quotation';

    public function sendData($data)
    {
        if($data instanceof InvalidRequestException) {
            $response = parent::handleRequestException($data, 'InvalidRequestException');
            return $this->createResponse($response);
        }
        try {
            $xero = $this->createXeroApplication();

            if ($this->getAccountingID()) {
                $quotes = $xero->loadByGUID(Quote::class, $this->getAccountingID());
            }
            elseif ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $quotes = $xero->loadByGUID(Quote::class, $this->getAccountingIDs());
                }
                else {
                    $quotes = $xero->loadByGUIDs(Quote::class, $this->getAccountingIDs());
                }
            } else {
                if($this->getSearchParams() || $this->getSearchFilters())
                {
                    $query = SearchBuilder::buildSearchQuery(
                        $xero,
                        Quote::class,
                        $this->getSearchParams(),
                        $this->getExactSearchValue(),
                        $this->getSearchFilters(),
                        $this->getMatchAllFilters()
                    );
                    if ($this->getPage()) {
                        $quotes = $query->page($this->getPage())->execute();
                    } else {
                        $quotes = $query->execute();
                    }
                } else {
                    $quotes = $xero->load(Quote::class)->page($this->getPage())->execute();
                }
            }
            $response = $quotes;

        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }
        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return GetQuotationResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetQuotationResponse($this, $data);
    }

    public function getData()
    {
        // TODO: Implement getData() method.
    }
}
