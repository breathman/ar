<?php

namespace AppBundle\Event;

use JMS\Serializer\Annotation\Type;

class CarServiceSubscribeEvent extends CarServiceEvent
{
    /**
     * @var int
     *
     * @Type("integer")
     */
    protected $orderId;

    /**
     * @param int $id
     * @param int $orderId
     */
    public function __construct($id, $orderId)
    {
        parent::__construct($id);

        $this->orderId = $orderId;
    }

    /**
     * @return int
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s:{carService:%s, order:%s}', static::class, $this->getId(), $this->getOrderId());
    }
}
