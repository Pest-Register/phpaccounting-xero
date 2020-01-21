<?php

namespace PHPAccounting\Xero\Helpers;

use function GuzzleHttp\Psr7\str;

/**
 * Class ErrorResponseHelper
 * @package PHPAccounting\Xero\Helpers
 */
class ErrorResponseHelper
{
    /**
     * Parse error message and return generic response
     * @param $response
     * @param $model
     * @return string
     */
    public static function parseErrorResponse ($response, $model = '') {
        switch ($model) {
            case 'Account':
                if (strpos($response, 'Please enter a unique Name') !== false || strpos($response, 'Please enter a unique Code')) {
                    return 'Duplicate model found';
                } elseif(strpos($response, 'Account is not found') !== false) {
                    return 'No model found from given ID';
                }
                return $response;
                break;
            case 'Invoice':
                if (strpos($response, 'An existing Invoice with the specified InvoiceID could not be found') !== false) {
                    return 'No model found from given ID';
                } elseif(strpos($response, 'This document cannot be edited') !== false || strpos($response, 'The document date cannot be before the period lock date') !== false) {
                    return 'Model cannot be edited';
                }
                return $response;
                break;
            case 'Contact':
                if (strpos($response, 'The contact name must be unique') !== false) {
                    return 'Duplicate model found';
                }
                return $response;
                break;
            case 'Contact Group':
                if (strpos($response, 'An contact group by that name already exists') !== false) {
                    return 'Duplicate model found';
                }
                return $response;
                break;
            case 'Inventory Item':
                if (strpos($response, 'already exists') !== false || strpos($response, 'must be unique')) {
                    return 'Duplicate model found';
                }
                return $response;
                break;
            default:
                if (strpos('Please enter a unique', $response) !== false) {
                    return 'Duplicate model found';
                }
                return $response;
        }
    }
}