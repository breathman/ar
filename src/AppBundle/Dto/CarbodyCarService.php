<?php

namespace AppBundle\Dto;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("none")
 */
class CarbodyCarService
{
    /**
     * @var string
     */
    protected $ticker;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $ticker
     *
     * @return $this
     */
    public function setTicker($ticker)
    {
        $this->ticker = $ticker;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
