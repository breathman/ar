<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="order_status")
 */
final class OrderStatus extends AbstractStatus
{
    const BLANK    = 'blank';
    const ESTIMATE = 'estimate';
    const PENDING  = 'pending';
    const ACCEPTED = 'accepted';
    const REJECTED = 'rejected';
    const CLOSED   = 'closed';

    /**
     * @return string
     */
    public function getDescription()
    {
        static $description = array(
            self::BLANK    => 'Пустой',
            self::ESTIMATE => 'Эстимейт',
            self::PENDING  => 'В работе',
            self::ACCEPTED => 'Принят',
            self::REJECTED => 'Отклонён',
            self::CLOSED   => 'Закрыт',
        );

        return $description[$this->getId()];
    }
}
