<?php

namespace AppBundle\Controller;

use AppBundle\Dto\OrderEdit;
use AppBundle\Repository\Query\CarServiceAll;
use AppBundle\Repository\Query\PackageDetailAllCarbody;
use AppBundle\Service\Command\OrderCreate;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Service\UserKeyService;

class CabinetControllerTest extends WebTestCase
{
    /**
     * @return string
     */
    public function testCreate()
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        $userKeyService = $this->getMockBuilder(UserKeyService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userKeyService->method('create')->willReturn(uniqid());

        $works = call_user_func(function() use ($entityManager) {
            $allWorks = (new PackageDetailAllCarbody($entityManager))->execute();
            shuffle($allWorks);

            return array_map(function($_) use (&$allWorks) {
                return array_shift($allWorks)->getId();
            },
                range(1, rand(1, 3))
            );
        });

        $command = new OrderCreate(10, 10, $userKeyService, $entityManager);
        $command->execute(new OrderEdit(null, $works, null, null, null, null, null, null, null, null));

        $this->assertNotNull($order = $command->getOrder());

        return $order->getUserKey();
    }

    public function testEstimatesBrokenInterval()
    {
        $client = static::createClient();
        $client->request('GET', '/api/cabinet/estimates', [
            'interval' => uniqid(),
        ]);

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testEstimates()
    {
        $client = static::createClient();
        $client->request('GET', '/api/cabinet/estimates');

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testEstimatesInterval()
    {
        $client = static::createClient();
        $client->request('GET', '/api/cabinet/estimates', [
            'interval' => 'P3D',
        ]);

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    /**
     * @param string $userKey
     *
     * @depends testCreate
     */
    public function testEstimatesKey($userKey)
    {
        $client = static::createClient();
        $client->request('GET', '/api/cabinet/estimates', [
            'key' => [
                $userKey,
            ],
        ]);

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $this->assertCount(1, $orders = json_decode($response->getContent()));
        $this->assertEquals($userKey, $key = reset($orders)->key);
    }

    /**
     * @param string $userKey
     *
     * @depends testCreate
     */
    public function testSubscribeBrokenCost($userKey)
    {
        $client = static::createClient();

        $allCarServices = (new CarServiceAll($client->getContainer()->get('doctrine.orm.entity_manager')))->execute();
        $ticker = reset($allCarServices)->getTicker();

        $client->request('POST', '/api/cabinet/subscribe', $params = array(
            'service_key' => $ticker,
            'order_key'   => $userKey,
            'cost'        => 5000,
        ));

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    /**
     * @param string $userKey
     *
     * @return array
     *
     * @depends testCreate
     */
    public function testSubscribe($userKey)
    {
        $client = static::createClient();

        $allCarServices = (new CarServiceAll($client->getContainer()->get('doctrine.orm.entity_manager')))->execute();
        $ticker = reset($allCarServices)->getTicker();

        $client->request('POST', '/api/cabinet/subscribe', $params = array(
            'service_key' => $ticker,
            'order_key'   => $userKey,
            'cost'        => 5000,
            'note'        => 'Это обоснование новой цены',
        ));

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        return $params;
    }

    /**
     * @param array $params
     *
     * @depends testSubscribe
     */
    public function testSubscribeAlready(array $params)
    {
        $client = static::createClient();
        $client->request('POST', '/api/cabinet/subscribe', [
            'service_key' => $params['service_key'],
            'order_key'   => $params['order_key'],
        ]);

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    /**
     * @param array $params
     *
     * @depends testSubscribe
     */
    public function testSubscribeBrokenOrder(array $params)
    {
        $client = static::createClient();
        $client->request('POST', '/api/cabinet/subscribe', [
            'service_key' => $params['service_key'],
            'order_key'   => uniqid(),
        ]);

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testOrdersBrokenTicker()
    {
        $client = static::createClient();
        $client->request('GET', '/api/cabinet/orders');

        $response = $client->getResponse();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    /**
     * @param array $params
     *
     * @depends testSubscribe
     */
    public function testOrders(array $params)
    {
        $client = static::createClient();
        $client->request('GET', '/api/cabinet/orders', [
            'service_key' => $params['service_key'],
        ]);

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $orders = json_decode($response->getContent());
        $this->assertTrue(count($orders) > 0);
    }
}
