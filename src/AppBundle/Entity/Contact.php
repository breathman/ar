<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="contact",
 *     indexes={
 *         @ORM\Index(columns={"value"})
 *     }
 * )
 */
class Contact
{
    use Common\Identifiable;
    use Common\TimeTrackable;
    use Common\Stringable;

    /**
     * @var ContactType
     *
     * @ORM\Column(name="type", type="contact_type", nullable=false)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=50, nullable=true)
     */
    protected $value;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=1024, nullable=true)
     */
    protected $note;

    /**
     * @param ContactType $type
     * @param string      $value
     * @param string      $name
     * @param string      $note
     */
    public function __construct(ContactType $type, $value, $name = null, $note = null)
    {
        $this->setType($type);

        $this->value = $value;
        $this->name  = $name;
        $this->note  = $note;
    }

    /**
     * @return ContactType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ContactType $type
     *
     * @return bool
     */
    public function isType(ContactType $type)
    {
        return $type->equals($this->getType());
    }

    /**
     * @param ContactType $type
     *
     * @return $this
     */
    public function setType(ContactType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @return string
     */
    public function getNote()
    {
        return $this->note;
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

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%s:{%s,`%s`,`%s`,`%s`}', static::class, $this->id, $this->type, $this->value, $this->name);
    }
}
