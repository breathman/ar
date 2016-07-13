<?php

namespace AppBundle\Entity;

use AppBundle\Type\Enum;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractStatus extends Enum
{
    use Common\Stringable;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="string", unique=true, nullable=false)
     * @ORM\Id
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", unique=true, nullable=false)
     */
    protected $description;

    /**
     * @param int    $id
     * @param string $description
     */
    public function __construct($id, $description = null)
    {
        parent::__construct($id);

        $this->description = $description;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return (string) $this->id;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
