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
                if (strpos($response, 'Please enter a unique Name') !== false || strpos($response, 'Please enter a unique Code') !== false) {
                    return 'Duplicate model found';
                } elseif(strpos($response, 'Account is not found') !== false) {
                    return 'No model found from given ID';
                } elseif(strpos($response, 'Can only update STATUS on Archived accounts') !== false ||
                    strpos($response, 'Cannot archive System accounts') !== false ||
                    strpos($response, 'Cannot update Bank Accounts') !== false ||
                    strpos($response, 'Cannot update account details and STATUS on the same request') !== false
                ) {
                    return 'Model cannot be edited';
                } elseif (strpos($response, 'TokenExpired') !== false || strpos($response, 'You are not permitted to access this resource') !== false) {
                    return 'The access token has expired';
                }
                return $response;
                break;
            case 'Invoice':
                if (strpos($response, 'An existing Invoice with the specified InvoiceID could not be found') !== false) {
                    return 'No model found from given ID';
                } elseif(strpos($response, 'This document cannot be edited') !== false || strpos($response, 'Invoice not of valid status for modification') !== false ||
                    strpos($response, 'The document date cannot be before the period lock date') !== false ||
                    strpos($response, 'Invoice not of valid status for modification') !== false) {
                    return 'Model cannot be edited';
                } elseif (strpos($response, 'TokenExpired') !== false || strpos($response, 'You are not permitted to access this resource') !== false) {
                    return 'The access token has expired';
                }
                return $response;
                break;
            case 'Contact':
                if (strpos($response, 'The contact name must be unique') !== false) {
                    return 'Duplicate model found';
                } elseif (strpos($response, 'TokenExpired') !== false || strpos($response, 'You are not permitted to access this resource') !== false) {
                    return 'The access token has expired';
                }
                return $response;
                break;
            case 'Contact Group':
                if (strpos($response, 'An contact group by that name already exists') !== false ||
                    strpos($response, 'A Contact Group already exists with this name') !== false) {
                    return 'Duplicate model found';
                } elseif (strpos($response, 'TokenExpired') !== false || strpos($response, 'You are not permitted to access this resource') !== false) {
                    return 'The access token has expired';
                }
                return $response;
                break;
            case 'Inventory Item':
                if (strpos($response, 'already exists') !== false || strpos($response, 'must be unique') !== false) {
                    return 'Duplicate model found';
                } elseif (strpos($response, 'TokenExpired') !== false || strpos($response, 'You are not permitted to access this resource') !== false) {
                    return 'The access token has expired';
                }
                return $response;
                break;
            case 'Payment':
                if (strpos($response, 'Payment amount exceeds the amount outstanding on this document') !== false || strpos($response, 'Payment not of valid status for modification') !== false) {
                    return 'Model cannot be edited';
                } elseif (strpos($response, 'TokenExpired') !== false || strpos($response, 'You are not permitted to access this resource') !== false) {
                    return 'The access token has expired';
                }
                return $response;
            default:
                if (strpos('Please enter a unique', $response) !== false) {
                    return 'Duplicate model found';
                } elseif (strpos($response, 'TokenExpired') !== false || strpos($response, 'You are not permitted to access this resource') !== false) {
                    return 'The access token has expired';
                }
                return $response;
        }
    }
}