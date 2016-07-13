<?php

namespace AppBundle\Doctrine\DBAL\Types;

class ContactType extends Enum
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'contact_type';
    }

    public function getClass()
    {
        return \AppBundle\Entity\ContactType::class;
    }
}
