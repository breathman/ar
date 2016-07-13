<?php

namespace AppBundle\Controller;

use AppBundle\Repository\Query\DetailAllCarbody;
use AppBundle\Repository\Query\PackageDetailAllCarbody;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CarbodyControllerTest extends WebTestCase
{
    public function testWorks()
    {
        $client = static::createClient();
        $query = new PackageDetailAllCarbody($client->getContainer()->get('doctrine.orm.entity_manager'));

        $client->request('GET', '/api/carbody/packages');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(count($query->execute()), count(json_decode($response->getContent())));
    }

    public function testDetails()
    {
        $client = static::createClient();
        $query = new DetailAllCarbody($client->getContainer()->get('doctrine.orm.entity_manager'));

        $client->request('GET', '/api/carbody/details');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(count($query->execute()), count(json_decode($response->getContent())));
    }
}
