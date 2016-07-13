<?php

namespace AppBundle\Entity\Common;

use Doctrine\ORM\Mapping as ORM;

trait Identifiable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isId($id)
    {
        return ($id === $this->id);
    }

    /**
     * @param mixed $other
     *
     * @return bool
     */
    public function equals($other)
    {
        if (true
            and is_object($other)
            and (false
                or (get_class($other) === static::class)
                or is_subclass_of($other, static::class)
            )
        ) {
            /** @var $other Identifiable */
            return $this->isId($other->getId());
        }

        return false;
    }
}
