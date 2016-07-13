<?php

namespace AppBundle\Dto;

use JMS\Serializer\Annotation as JMS;
use DateTime;

/**
 * @JMS\ExclusionPolicy("none")
 */
class OrderEstimate
{
    /**
     * @var float
     */
    protected $cost;

    /**
     * @var DateTime
     */
    protected $time;

    /**
     * @var CarbodyPackage[]
     */
    protected $packages;

    /**
     * @var OrderCar
     */
    protected $car;

    /**
     * @param float $cost
     *
     * @return OrderEstimate
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @param DateTime $time
     *
     * @return OrderEstimate
     */
    public function setTime(DateTime $time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * @param CarbodyPackage[] $packages
     *
     * @return OrderEstimate
     */
    public function setPackages(array $packages)
    {
        $this->packages = $packages;

        return $this;
    }

    /**
     * @param OrderCar $car
     *
     * @return OrderEstimate
     */
    public function setCar($car)
    {
        $this->car = $car;

        return $this;
    }
}
