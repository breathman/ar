<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Detail;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadDetailData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::getData() as $name) {
            $detail = new Detail($name);
            $manager->persist($detail);

            $this->addReference($detail->getName(), $detail);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public static function getData()
    {
        return [
            'Крыло',
            'Дверь',
            'Крыша',
            'Бампер',
            'Капот',
            'Багажник',
            'Боковая панель',
            'Порог',
            'Силовая конструкция',
        ];
    }
}
