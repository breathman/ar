<?php

namespace AppBundle\Dto;

use JMS\Serializer\Annotation as JMS;
use DateTime;

/**
 * @JMS\ExclusionPolicy("none")
 */
class OrderFile
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var DateTime
     */
    protected $time;

    /**
     * @var string
     */
    protected $mime;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $note;

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

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
     * @param string $mime
     *
     * @return $this
     */
    public function setMime($mime)
    {
        $this->mime = $mime;

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
     * @param string $note
     *
     * @return $this
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }
}
