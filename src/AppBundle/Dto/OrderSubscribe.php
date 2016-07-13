<?php

namespace AppBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class OrderSubscribe
{
    /**
     * @var string
     *
     * @Assert\Type(type="string")
     * @Assert\Length(min=2, max=50)
     */
    protected $orderKey;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=50)
     */
    protected $serviceKey;

    /**
     * @var int
     *
     * @Assert\Type(type="integer")
     */
    protected $cost;

    /**
     * @var string
     *
     * @Assert\Type(type="string")
     * @Assert\Length(max=1024)
     */
    protected $note;

    /**
     * @param string $orderKey
     * @param string $serviceKey
     * @param string $cost
     * @param string $note
     */
    public function __construct(
        $orderKey,
        $serviceKey,
        $cost,
        $note
    ) {
        $this->orderKey   = $orderKey;
        $this->serviceKey = $serviceKey;
        $this->cost       = $cost;
        $this->note       = $note;
    }

    /**
     * @return bool
     *
     * @Assert\IsTrue(message="Нельзя менять стоимость без обоснования")
     */
    public function isCostNoted()
    {
        return ($this->hasCost() and $this->hasNote());
    }

    /**
     * @return string
     */
    public function getOrderKey()
    {
        return $this->orderKey;
    }

    /**
     * @return string
     */
    public function getServiceKey()
    {
        return $this->serviceKey;
    }

    /**
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @return bool
     */
    public function hasCost()
    {
        return ! empty($this->cost);
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return bool
     */
    public function hasNote()
    {
        return ! empty($this->note);
    }
}
