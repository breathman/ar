<?php

namespace AppBundle\Event;

use JMS\Serializer\Annotation\Type;
use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

class CarServiceEvent extends SymfonyEvent
{
    /**
     * @var int
     *
     * @Type("integer")
     */
    protected $id;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s:{order:%s}', static::class, $this->getId());
    }
}
