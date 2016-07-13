<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ContactType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadContactTypeData extends AbstractFixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 70;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::getData() as $row) { list($id, $description) = $row;
            $manager->persist(new ContactType($id, $description));
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public static function getData()
    {
        return [
            [ContactType::PHONE, 'Телефон'],
            [ContactType::EMAIL, 'Электронная почта'],
        ];
    }
}
