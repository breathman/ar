<?php

namespace AppBundle\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class OrderList extends OrderEstimates
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="2", max="50")
     */
    protected $ticker;

    /**
     * @param string $interval
     * @param int[]  $keys
     * @param string $ticker
     */
    public function __construct($interval, $keys, $ticker)
    {
        parent::__construct($interval, $keys);
        
        $this->ticker = $ticker;
    }

    /**
     * @return string
     */
    public function getTicker()
    {
        return $this->ticker;
    }
}
