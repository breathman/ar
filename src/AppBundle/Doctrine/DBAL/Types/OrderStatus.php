<?php

namespace AppBundle\Doctrine\DBAL\Types;

class OrderStatus extends Enum
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'order_status';
    }

    public function getClass()
    {
        return \AppBundle\Entity\OrderStatus::class;
    }
}
