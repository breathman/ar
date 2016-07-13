<?php

namespace AppBundle\Dto;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("none")
 */
class CarbodyDetail extends CarbodyIdName
{
    /**
     * @var CarbodyIdName[]
     */
    protected $works;

    /**
     * @param CarbodyIdName[] $works
     *
     * @return $this
     */
    public function setWorks(array $works)
    {
        $this->works = $works;

        return $this;
    }
}
