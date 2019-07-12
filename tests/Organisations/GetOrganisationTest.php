<?php
/**
 * Created by IntelliJ IDEA.
 * User: Dylan
 * Date: 12/07/2019
 * Time: 9:31 AM
 */

namespace Tests\Organisations;


use Tests\BaseTest;

class GetOrganisationTest extends BaseTest
{

    public function testGetOrganisations()
    {
        $this->setUp();
        try {

            $response = $this->gateway->getOrganisation()->send();
            if ($response->isSuccessful()) {
                var_dump($response->getOrganisations());
            } else {
                var_dump($response->getErrorMessage());
            }
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }
    }
}