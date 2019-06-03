<?php

namespace Tests;


class GetContactGroupsTest extends BaseTest
{

    public function testGetContactGroups()
    {
        $this->setUp();
        try {
            $params = [
                'accountingIDs' => ["fe1ff7bb-a2bf-46c9-b2d8-b82245cf7b3c"],
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
