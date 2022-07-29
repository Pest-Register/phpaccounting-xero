<?php

namespace PHPAccounting\Xero\Helpers;

use XeroPHP\Application;

/**
 * Builds search / filter query based on search parameters and filters
 * @param Application $xero
 * @return \XeroPHP\Remote\Query
 */
class SearchQueryBuilder
{
    public static function buildSearchQuery(Application $xero, $modelClass, $searchParams, $exactSearch, $searchFilters, $exactFilter) {
        // Set contains query for partial matching
        $query = $xero->load($modelClass);
        $queryCounter = 0;
        if ($searchParams)
        {
            foreach($searchParams as $key => $value)
            {
                if($exactSearch || is_bool($value))
                {
                    if (is_bool($value)) {
                        $searchQuery = $key.'='.($value ? 'true' : 'false');
                    } else {
                        $searchQuery = $key.'="'.$value.'"';
                    }
                }
                else {
                    $searchQuery = $key.'.ToLower().Contains("'.strtolower($value).'")';
                }

                if ($queryCounter == 0)
                {
                    $query = $query->where($searchQuery);
                } else {
                    $query = $query->orWhere($searchQuery);
                }
                $queryCounter++;
            }
        }
        // If there are specific filters, add them to query
        $queryCounter = 0;
        if ($searchFilters)
        {
            foreach($searchFilters as $key => $value)
            {
                $queryString = '';
                $filterKey = $key;
                $innerQueryCounter = 0;
                if (is_array($value)) {
                    foreach($value as $filterValue)
                    {
                        if (str_ends_with($filterKey, 'ID')) {
                            $searchQuery = $filterKey.'=GUID("'.$filterValue.'")';
                        } else {
                            if (is_bool($filterValue)) {
                                $searchQuery = $filterKey.'='.($filterValue ? 'true' : 'false');
                            } else {
                                $searchQuery = $filterKey.'="'.$filterValue.'"';
                            }
                        }

                        if ($innerQueryCounter == 0)
                        {
                            $queryString = '('.$searchQuery;
                        } else {
                            if ($exactFilter)
                            {
                                $queryString.= ' AND '.$searchQuery;
                            }
                            else {
                                $queryString.= ' OR '.$searchQuery;
                            }
                        }
                        $innerQueryCounter++;
                    }
                    $queryString .= ')';
                } else {
                    if (str_ends_with($filterKey, 'ID')) {
                        $searchQuery = $filterKey.'=GUID("'.$value.'")';
                    } else {
                        if (is_bool($value)) {
                            $searchQuery = $filterKey.'='.($value ? 'true' : 'false');
                        } else {
                            $searchQuery = $filterKey.'="'.$value.'"';
                        }
                    }
                    $queryString = $searchQuery;
                    $queryCounter++;
                }
                if ($exactFilter)
                {
                    $query->andWhere($queryString);
                }
                else {
                    $query->orWhere($queryString);
                }
            }
        }
        return $query;
    }
}