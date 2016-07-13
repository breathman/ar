<?php

namespace AppBundle\Dto;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("none")
 */
class CarbodyPackage
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $detail;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string[]
     */
    protected $works;

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $detail
     *
     * @return $this
     */
    public function setDetail($detail)
    {
        $this->detail = $detail;

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

    /**
     * @param string[] $works
     *
     * @return $this
     */
    public function setWorks(array $works)
    {
        $this->works = $works;

        return $this;
    }
}
