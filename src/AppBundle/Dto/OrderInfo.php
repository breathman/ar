<?php

namespace AppBundle\Dto;

use JMS\Serializer\Annotation as JMS;
use DateTime;

/**
 * @JMS\ExclusionPolicy("none")
 */
class OrderInfo
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
     * @var OrderEstimate[]
     */
    protected $estimates;

    /**
     * @var OrderContact[]
     */
    protected $contacts;

    /**
     * @var OrderNote[]
     */
    protected $notes;

    /**
     * @var OrderFile[]
     */
    protected $files;

    /**
     * @var CarbodySubscribe[]
     */
    protected $subscribes;

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
     * @param OrderEstimate[] $estimates
     *
     * @return $this
     */
    public function setEstimates(array $estimates)
    {
        $this->estimates = $estimates;

        return $this;
    }

    /**
     * @param OrderContact[] $contacts
     *
     * @return $this
     */
    public function setContacts(array $contacts)
    {
        $this->contacts = $contacts;

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

    /**
     * @param OrderFile[] $files
     *
     * @return $this
     */
    public function setFiles(array $files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * @param CarbodySubscribe[] $subscribes
     *
     * @return $this
     */
    public function setSubscribes(array $subscribes)
    {
        $this->subscribes = $subscribes;

        return $this;
    }
}
