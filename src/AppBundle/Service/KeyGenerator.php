<?php

namespace AppBundle\Service;

/**
 * Генератор ключей имени Никиты-электроника
 */
class KeyGenerator
{
    /**
     * @var int
     */
    protected $min;

    /**
     * @var int
     */
    protected $max;

    /**
     * @param int $min
     * @param int $max
     */
    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @return string
     */
    public function generate()
    {
        static $symbols = [
            '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B',
            'C', 'E', 'F', 'H', 'J', 'K', 'L', 'M', 'N', 'P',
            'Q', 'R', 'S', 'T', 'X', 'Y', 'Z',
        ];

        $id = rand($this->min, $this->max);

        $result = '';
        do {
            $result = $symbols[bcmod($id, count($symbols))] . $result;
        } while ($id = bcdiv($id, count($symbols), 0));

        return $result;
    }
}
