<?php

namespace AppBundle\Dto;

use JMS\Serializer\Annotation as JMS;
use DateTime;

/**
 * @JMS\ExclusionPolicy("none")
 */
class CarbodySubscribe
{
    /**
     * @var DateTime
     */
    protected $time;

    /**
     * @var int
     */
    protected $cost;

    /**
     * @var CarbodyCarService
     */
    protected $service;

    /**
     * @var OrderNote[]
     */
    protected $notes;

    /**
     * @param DateTime $time
     *
     * @return $this
     */
    public function setTime(DateTime $time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @param int $cost
     *
     * @return $this
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @param CarbodyCarService $service
     *
     * @return $this
     */
    public function setCarService(CarbodyCarService $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @param OrderNote[] $notes
     *
     * @return $this
     */
    public function setNotes(array $notes)
    {
        $this->notes = $notes;

        return $this;
    }
}
