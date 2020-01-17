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
                } elseif(strpos($response, 'A validation exception occurred (Can only update STATUS on Archived accounts)') !== false ||
                    strpos($response, 'A validation exception occurred (Cannot archive System accounts)') !== false ||
                    strpos($response, 'A validation exception occurred (Cannot update Bank Accounts)') !== false) {
                    return 'Model cannot be edited';
                }
                return $response;
                break;
            case 'Invoice':
                if (strpos($response, 'An existing Invoice with the specified InvoiceID could not be found') !== false) {
                    return 'No model found from given ID';
                } elseif(strpos($response, 'This document cannot be edited') !== false || strpos($response, 'A validation exception occurred (Invoice not of valid status for modification)')) {
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
                if (strpos($response, 'An contact group by that name already exists') !== false ||
                    strpos($response, 'A validation exception occurred (A Contact Group already exists with this name)') !== false) {
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