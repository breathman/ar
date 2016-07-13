<?php

namespace AppBundle\Entity\Common;

use Doctrine\ORM\Mapping as ORM;

trait Stringable
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    abstract public function toString();
}
