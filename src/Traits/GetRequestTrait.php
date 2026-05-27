<?php

namespace PHPAccounting\Xero\Traits;

trait GetRequestTrait
{
    /**
     * Set AccountingID from Parameter Bag (ItemId generic interface)
     * @see https://developer.xero.com/documentation/api/accounts
     * @param $value
     */
    public function setAccountingID($value) {
        return $this->setParameter('accounting_id', $value);
    }

    /**
     * Get Accounting ID Parameter from Parameter Bag (AccountID generic interface)
     * @see https://developer.xero.com/documentation/api/accounts
     * @return mixed
     */
    public function getAccountingID() {
        return  $this->getParameter('accounting_id');
    }

    /**
     * Set AccountingID from Parameter Bag (AccountID generic interface)
     * @see https://developer.xero.com/documentation/api/accounts
     * @param $value
     */
    public function setAccountingIDs($value) {
        return $this->setParameter('accounting_ids', $value);
    }

    /**
     * Set SearchFilters from Parameter Bag (interface for query-based searching)
     * @see https://developer.xero.com/documentation/api/requests-and-responses#get-modified
     * @param $value
     */
    public function setSearchFilters($value) {
        return $this->setParameter('search_filters', $value);
    }

    /**
     * Return Search Filters for query-based searching
     * @return array
     */
    public function getSearchFilters() {
        return $this->getParameter('search_filters');
    }

    /**
     * Set SearchParams from Parameter Bag (interface for query-based searching)
     * @see https://developer.xero.com/documentation/api/requests-and-responses#get-modified
     * @param $value
     */
    public function setSearchParams($value) {
        return $this->setParameter('search_params', $value);
    }
    /**
     * Return Search Parameters for query-based searching
     * @return array
     */
    public function getSearchParams() {
        return $this->getParameter('search_params');
    }

    /**
     * Set boolean to determine partial or exact query based searches
     * @param $value
     */
    public function setExactSearchValue($value) {
        return $this->setParameter('exact_search_value', $value);
    }

    /**
     * Get boolean to determine partial or exact query based searches
     * @return mixed
     */
    public function getExactSearchValue() {
        return $this->getParameter('exact_search_value');
    }

    /**
     * Set boolean to determine whether all filters need to be matched
     * @param $value
     */
    public function setMatchAllFilters($value) {
        return $this->setParameter('match_all_filters', $value);
    }

    /**
     * Get boolean to determine whether all filters need to be matched
     * @return mixed
     */
    public function getMatchAllFilters() {
        return $this->getParameter('match_all_filters');
    }

    /**
     * Set Page Value for Pagination from Parameter Bag
     * @see https://developer.xero.com/documentation/api/accounts
     * @param $value
     */
    public function setPage($value) {
        return $this->setParameter('page', $value);
    }

    /**
     * Return Comma Delimited String of Accounting IDs (AccountIDs)
     * @return mixed comma-delimited-string
     */
    public function getAccountingIDs() {
        if ($this->getParameter('accounting_ids')) {
            return implode(', ',$this->getParameter('accounting_ids'));
        }
        return null;
    }

    /**
     * Return Page Value for Pagination
     * @return integer
     */
    public function getPage() {
        return $this->getParameter('page');
    }

    /**
     * Set the modified-after cutoff for delta/incremental sync. Subsequent
     * list-style GET requests will pass this to XeroPHP's
     * `Query::modifiedAfter($dt)`, which translates to the Xero
     * `If-Modified-Since` header and restricts the response to records
     * updated at or after this datetime.
     *
     * Accepts a DateTimeInterface, a string parsable by DateTime, or null.
     */
    public function setLastModifiedSince($value) {
        return $this->setParameter('last_modified_since', $value);
    }

    /**
     * Get the modified-after cutoff as a DateTime (or null when not set).
     * Returning a DateTime here keeps the call site simple — request classes
     * pass straight into `$query->modifiedAfter(...)`.
     */
    public function getLastModifiedSinceDateTime(): ?\DateTime {
        $value = $this->getParameter('last_modified_since');
        if ($value === null || $value === '') {
            return null;
        }
        if ($value instanceof \DateTime) {
            return $value;
        }
        if ($value instanceof \DateTimeInterface) {
            return \DateTime::createFromInterface($value);
        }
        try {
            return new \DateTime((string) $value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}