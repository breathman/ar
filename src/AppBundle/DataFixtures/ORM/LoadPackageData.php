<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Package;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPackageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 30;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::getData() as $name) {
            $package = new Package($name);
            $manager->persist($package);

            $this->addReference($package->getName(), $package);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public static function getData()
    {
        return [
            'Мелкие царапины',
            'Глубокие царапины',
            'Вмятины без повреждения ЛКП',
            'Легкие повреждения',
            'Средние повреждения',
            'Тяжелые повреждения',
            'Особые повреждения',
        ];
    }
}
