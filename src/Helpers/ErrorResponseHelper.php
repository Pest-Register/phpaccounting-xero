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
     * @return array
     */
    public static function parseErrorResponse ($response, $type, $status, $errorCode, $statusCode, $detail, $exception, $model = '') {
        if ($type == 'RateLimitExceeded') {
            return [
                'message' => $response,
                'exception' => $response,
                'rate_problem' => $exception['rate_problem'],
                'retry' => $exception['retry']
            ];
        } else {
            switch ($model) {
                case 'Account':
                    if (strpos($response, 'Please enter a unique Name') !== false || strpos($response, 'Please enter a unique Code') !== false) {
                        return [
                            'message' => 'Duplicate model found',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    } elseif(strpos($response, 'Account is not found') !== false) {
                        return [
                            'message' => 'No model found from given ID',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    } elseif(strpos($response, 'Can only update STATUS on Archived accounts') !== false ||
                        strpos($response, 'Cannot archive System accounts') !== false ||
                        strpos($response, 'Cannot update Bank Accounts') !== false ||
                        strpos($response, 'Cannot update account details and STATUS on the same request') !== false ||
                        strpos($response, 'Account cannot be updated to Inventory') !== false)
                    {
                        return [
                            'message' => 'Model cannot be edited',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    } elseif (strpos($response, 'TokenExpired') !== false || strpos($response, 'You are not permitted to access this resource') !== false) {
                        return [
                            'message' => 'The access token has expired',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    }
                    return [
                        'message' => $response,
                        'status' => $status,
                        'exception' => $response,
                        'error_code' => $errorCode,
                        'status_code' => $statusCode,
                        'detail'=> $detail
                    ];
                    break;
                case 'Invoice':
                    if (strpos($response, 'An existing Invoice with the specified InvoiceID could not be found') !== false) {
                        return [
                            'message' => 'No model found from given ID',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    } elseif(strpos($response, 'This document cannot be edited') !== false || strpos($response, 'Invoice not of valid status for modification') !== false ||
                        strpos($response, 'The document date cannot be before the period lock date') !== false ||
                        strpos($response, 'Invoice not of valid status for modification') !== false) {
                        return [
                            'message' => 'Model cannot be edited',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    } elseif (strpos($response, 'TokenExpired') !== false || strpos($response, 'You are not permitted to access this resource') !== false) {
                        return [
                            'message' => 'The access token has expired',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    }
                    return [
                        'message' => $response,
                        'status' => $status,
                        'exception' => $response,
                        'error_code' => $errorCode,
                        'status_code' => $statusCode,
                        'detail'=> $detail
                    ];
                    break;
                case 'Contact':
                    if (strpos($response, 'The contact name must be unique') !== false) {
                        return [
                            'message' => 'Duplicate model found',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    } elseif (strpos($response, 'TokenExpired') !== false || strpos($response, 'You are not permitted to access this resource') !== false) {
                        return [
                            'message' => 'The access token has expired',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    }
                    return [
                        'message' => $response,
                        'status' => $status,
                        'exception' => $response,
                        'error_code' => $errorCode,
                        'status_code' => $statusCode,
                        'detail'=> $detail
                    ];
                    break;
                case 'Contact Group':
                    if (strpos($response, 'An contact group by that name already exists') !== false ||
                        strpos($response, 'A Contact Group already exists with this name') !== false) {
                        return [
                            'message' => 'Duplicate model found',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    } elseif (strpos($response, 'TokenExpired') !== false || strpos($response, 'You are not permitted to access this resource') !== false) {
                        return [
                            'message' => 'The access token has expired',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    }
                    return [
                        'message' => $response,
                        'status' => $status,
                        'exception' => $response,
                        'error_code' => $errorCode,
                        'status_code' => $statusCode,
                        'detail'=> $detail
                    ];
                    break;
                case 'Inventory Item':
                    if (strpos($response, 'already exists') !== false || strpos($response, 'must be unique') !== false) {
                        return [
                            'message' => 'Duplicate model found',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    } elseif (strpos($response, 'TokenExpired') !== false || strpos($response, 'You are not permitted to access this resource') !== false) {
                        return [
                            'message' => 'The access token has expired',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    }
                    return [
                        'message' => $response,
                        'status' => $status,
                        'exception' => $response,
                        'error_code' => $errorCode,
                        'status_code' => $statusCode,
                        'detail'=> $detail
                        ];
                    break;
                case 'Payment':
                    if (strpos($response, 'Payment amount exceeds the amount outstanding on this document') !== false || strpos($response, 'Payment not of valid status for modification') !== false) {
                        return [
                            'message' => 'Model cannot be edited',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    } elseif (strpos($response, 'TokenExpired') !== false || strpos($response, 'You are not permitted to access this resource') !== false) {
                        return [
                            'message' => 'The access token has expired',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    }
                    return [
                        'message' => $response,
                        'status' => $status,
                        'exception' => $response,
                        'error_code' => $errorCode,
                        'status_code' => $statusCode,
                        'detail'=> $detail
                    ];
                default:
                    if (strpos('Please enter a unique', $response) !== false) {
                        return [
                            'message' => 'Duplicate model found',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    } elseif (strpos($response, 'TokenExpired') !== false || strpos($response, 'You are not permitted to access this resource') !== false) {
                        return [
                            'message' => 'The access token has expired',
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    } elseif (strpos($response, 'parameter is required') !== false) {
                        return [
                            'message' => $response,
                            'status' => $status,
                            'exception' => $response,
                            'error_code' => $errorCode,
                            'status_code' => $statusCode,
                            'detail'=> $detail
                        ];
                    }
                    return [
                        'message' => $response,
                        'status' => $status,
                        'exception' => $response,
                        'error_code' => $errorCode,
                        'status_code' => $statusCode,
                        'detail'=> $detail
                    ];
            }
        }
    }
}