<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\CarService;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCarServiceData extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 100;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::getData() as $row) { list($key, $name) = $row;
            $manager->persist(new CarService($key, $name));
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public static function getData()
    {
        return [
            ['AUTOPLANETARECHNOY', 'Авто-Планета на Речном'],
            ['YAUSAMOTORSVOLGOGR', 'Яуза-Моторс на Волгоградке'],
        ];
    }
}
