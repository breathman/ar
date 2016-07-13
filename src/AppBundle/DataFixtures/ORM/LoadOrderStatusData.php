<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\OrderStatus;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadOrderStatusData extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 60;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::getData() as $row) {
            list($id, $description) = $row;

            $manager->persist(new OrderStatus($id, $description));
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public static function getData()
    {
        return array_map(function($id) {
            $status = new OrderStatus($id);

            return [$status->getId(), $status->getDescription()];
        },
            OrderStatus::all()
        );
    }
}
