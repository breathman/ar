<?php

namespace AppBundle\Service;

use Assert\Assertion;
use TCPDF2DBarcode;

class CodeGenerator
{
    /**
     * @var int
     */
    protected $size;

    /**
     * @var string
     */
    protected $type;

    /**
     * @param string $type
     * @param int    $size
     */
    public function __construct($type, $size)
    {
        $this->type = $type;
        $this->size = $size;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function generate($string)
    {
        Assertion::string($string);

        return (new TCPDF2DBarcode($string, $this->type))
            ->getBarcodePngData($this->size, $this->size);
    }
}
