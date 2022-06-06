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
                if($exactSearch)
                {
                    $searchQuery = $key.'="'.$value.'"';
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
                if (is_array($value)) {
                    foreach($value as $filterValue)
                    {
                        if (str_ends_with($filterKey, 'ID')) {
                            $searchQuery = $filterKey.'=GUID("'.$filterValue.'")';
                        } else {
                            $searchQuery = $filterKey.'="'.$filterValue.'"';
                        }

                        if ($queryCounter == 0)
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
                        $queryCounter++;
                    }
                } else {
                    if (str_ends_with($filterKey, 'ID')) {
                        $searchQuery = $filterKey.'=GUID("'.$value.'")';
                    } else {
                        $searchQuery = $filterKey.'="'.$value.'"';
                    }
                    if ($queryCounter == 0)
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
                    $queryCounter++;
                }
                $queryString.=")";
                $query->andWhere($queryString);
            }
        }
        return $query;
    }
}