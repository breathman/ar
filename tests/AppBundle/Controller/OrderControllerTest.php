<?php

namespace AppBundle\Controller;

use AppBundle\Repository\Query\PackageDetailAllCarbody;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class OrderControllerTest extends WebTestCase
{
    /**
     * @return string
     */
    public function testBlank()
    {
        $client = static::createClient();

        $client->request('POST', '/api/carbody/order');

        $response = $client->getResponse();

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('location', $response->headers->all());
        $this->assertJson($response->getContent());
    }

    /**
     * @return string
     */
    public function testCreate()
    {
        $client = static::createClient();

        $works = call_user_func(function() use ($client) {
            $allWorks = (new PackageDetailAllCarbody($client->getContainer()->get('doctrine.orm.entity_manager')))->execute();

            return array_map(function($_) use (&$allWorks) {
                return array_shift($allWorks)->getId();
            },
                range(1, rand(1, 3))
            );
        });

        $pathToFiles = $client->getKernel()->getRootDir() . '/../tests/fixture';

        $client->request('POST', '/api/carbody/order', [
            'work'   => $works,
            'number' => 'A123BC',
            'brand'  => 'Skoda',
            'model'  => 'Octavia',
            'name'   => 'Имя контакта',
            'phone'  => '+79061112233',
            'email'  => 'email@example.com',
            'note'   => 'Это заметка при создании заказа',
        ], [
            'photo'  => [
                new UploadedFile($pathToFiles . '/file1.jpg', 'file1.jpg', 'image/jpeg'),
                new UploadedFile($pathToFiles . '/file2.jpg', 'file2.jpg', 'image/jpeg'),
            ]
        ]);

        $response = $client->getResponse();

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('location', $response->headers->all());
        $this->assertJson($response->getContent());

        return json_decode($response->getContent());
    }

    /**
     * @param string $userKey
     *
     * @return int
     *
     * @depends testCreate
     */
    public function testLoad($userKey)
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/api/carbody/order/%s', $userKey));

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getContent());
        $this->assertJson($response->getContent());

        $order = json_decode($response->getContent());

        $this->assertEquals($userKey, $order->key);
        $this->assertCount(1, $order->estimates);
        $this->assertCount(2, $order->contacts);
        $this->assertCount(2, $order->files);
        $this->assertCount(1, $order->notes);

        return reset($order->files)->key;
    }

    /**
     * @param string $userKey
     *
     * @depends testCreate
     */
    public function testCode($userKey)
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/api/carbody/code/%s', $userKey));

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getContent());
    }

    /**
     * @param string $key
     *
     * @depends testLoad
     */
    public function testFile($key)
    {
        $client = static::createClient();
        $client->request('GET', sprintf('/api/carbody/file/%s?dl', $key));

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNotEmpty($response->getContent());
        $this->assertArrayHasKey('content-disposition', $response->headers->all());
    }

    /**
     * @param string $userKey
     *
     * @depends testCreate
     */
    public function testUpdate($userKey)
    {
        $client = static::createClient();
        $client->request('POST', '/api/carbody/order', [
            'key'    => $userKey,
            'name'   => 'Новое имя контакта',
            'phone'  => '+79061112234',
            'email'  => 'new@example.com',
            'note'   => 'Это заметка при изменении заказа',
        ]);

        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEmpty($response->getContent());
    }
}
