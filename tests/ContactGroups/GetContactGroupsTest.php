<?php

namespace Tests;


class GetContactGroupsTest extends BaseTest
{

    public function testGetContactGroups()
    {
        $this->setUp();
        try {
            $params = [
                'accountingIDs' => [""],
                'page' => 1
            ];

            $response = $this->gateway->getContactGroup($params)->send();
            if ($response->isSuccessful()) {
                var_dump($response->getContactGroups());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}
