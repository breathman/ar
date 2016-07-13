<?php

namespace AppBundle\Dto;

use JMS\Serializer\Annotation as JMS;
use DateTime;

/**
 * @JMS\ExclusionPolicy("none")
 */
class OrderNote
{
    /**
     * @var DateTime
     */
    protected $time;

    /**
     * @var string
     */
    protected $body;

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
     * @param string $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }
}
