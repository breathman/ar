<?php

namespace AppBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use DateInterval;
use Exception;

class OrderEstimates
{
    /**
     * @var string
     */
    protected $interval;

    /**
     * @var int[]
     *
     * @Assert\Type(type="array")
     * @Assert\All({
     *     @Assert\Type(type="string", message="Заказы должны быть заданы пользовательскими ключами"),
     *     @Assert\Length(min="2", max="50")
     * })
     */
    protected $keys;

    /**
     * @param string $interval
     * @param int[]  $keys
     */
    public function __construct($interval, $keys)
    {
        $this->interval = $interval;
        $this->keys     = $keys;
    }


    /**
     * @return bool
     *
     * @Assert\IsTrue(message="Интервал должен быть задан в формате ISO-8601")
     */
    public function isIntervalValid()
    {
        return is_null($this->interval) or $this->hasInterval();
    }

    /**
     * @return bool
     */
    public function hasInterval()
    {
        try {
            return (bool) $this->getInterval();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return DateInterval
     *
     * @throws Exception
     */
    public function getInterval()
    {
        return new DateInterval($this->interval);
    }

    /**
     * @return int[]|null
     */
    public function getKeys()
    {
        return $this->keys;
    }
}
