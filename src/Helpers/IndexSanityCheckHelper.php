<?php

namespace PHPAccounting\Xero\Helpers;

/**
 * Class IndexSanityCheckHelper
 * @package PHPAccounting\Xero\Helpers
 */
class IndexSanityCheckHelper
{
    public static function indexSanityCheck ($key, $data) {
        $value = '';
        // Check if data passed in is an object or array
        if (is_object($data)) {
            if (property_exists($data, $key)) {
                return $data[$key];
            }
        }
        elseif (is_array($data)) {
            if (array_key_exists($key, $data)) {
                return $data[$key];
            }
        }
        return $value;
    }
}