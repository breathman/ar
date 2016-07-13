<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Work;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadWorkData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 20;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::getData() as $row) { list($cost, $name) = $row;
            $work = new Work($name, $cost);
            $manager->persist($work);

            $this->addReference($work->getName(), $work);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public static function getData()
    {
        return [
            [1200, 'Монтаж съемной детали'],
            [3000, 'Монтаж несъемной детали'],
            [1000, 'Вытягивание вмятины'],
            [3000, 'Подготовка'],
            [1200, 'Покраска'],
            [1500, 'Варка пластика'],
            [2500, 'Восстановление геометрии'],
            [ 600, 'Полировка'],
            [3600, 'Стапельные работы'],
            [ 800, 'Ремонт трещины на стекле'],
            [1000, 'Переборка внутренней начинки'],
            [2400, 'Замена стекла'],
            [ 400, 'Замена фары'],
            [ 400, 'Установка элементов декора'],
        ];
    }
}
