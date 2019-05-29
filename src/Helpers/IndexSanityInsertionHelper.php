<?php

namespace PHPAccounting\Xero\Helpers;

class IndexSanityInsertionHelper
{
    public static function indexSanityInsert ($key, $data, $model, $method) {
        $value = IndexSanityCheckHelper::indexSanityCheck($key, $data);
        if($value !== '') {
            $model->$method($value);
        }

        return $model;
    }
}