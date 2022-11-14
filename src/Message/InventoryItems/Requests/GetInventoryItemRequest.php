<?php

namespace PHPAccounting\Xero\Message\InventoryItems\Requests;
use Omnipay\Common\Exception\InvalidRequestException;
use PHPAccounting\Xero\Helpers\SearchQueryBuilder as SearchBuilder;
use PHPAccounting\Xero\Message\AbstractXeroRequest;
use PHPAccounting\Xero\Message\InventoryItems\Responses\GetInventoryItemResponse;
use PHPAccounting\Xero\Traits\GetRequestTrait;
use XeroPHP\Models\Accounting\Item;
use XeroPHP\Remote\Exception;

/**
 * Get Inventory Items(s)
 * @package PHPAccounting\XERO\Message\InventoryItems\Requests
 */
class GetInventoryItemRequest extends AbstractXeroRequest
{
    use GetRequestTrait;

    public string $model = 'InventoryItem';

    /**
     * Send Data to Xero Endpoint and Retrieve Response via Response Interface
     * @param mixed $data Parameter Bag Variables After Validation
     * @return GetInventoryItemResponse
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
                $items = $xero->loadByGUID(Item::class, $this->getAccountingID());
            }
            elseif ($this->getAccountingIDs()) {
                if(strpos($this->getAccountingIDs(), ',') === false) {
                    $items = $xero->loadByGUID(Item::class, $this->getAccountingIDs());
                }
                else {
                    $items = $xero->loadByGUIDs(Item::class, $this->getAccountingIDs());
                }
            } else {
                if($this->getSearchParams() || $this->getSearchFilters())
                {
                    $query = SearchBuilder::buildSearchQuery(
                        $xero,
                        Item::class,
                        $this->getSearchParams(),
                        $this->getExactSearchValue(),
                        $this->getSearchFilters(),
                        $this->getMatchAllFilters()
                    );
                    $items = $query->execute();
                } else {
                    $items = $xero->load(Item::class)->execute();
                }
            }
            $response = $items;

        } catch (Exception $exception) {
            $response = parent::handleRequestException($exception, get_class($exception));
            return $this->createResponse($response);
        }
        return $this->createResponse($response);
    }

    /**
     * Create Generic Response from Xero Endpoint
     * @param mixed $data Array Elements or Xero Collection from Response
     * @return GetInventoryItemResponse
     */
    public function createResponse($data)
    {
        return $this->response = new GetInventoryItemResponse($this, $data);
    }

    public function getData()
    {
        // TODO: Implement getData() method.
    }
}
