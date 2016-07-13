<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="car",
 *     indexes={
 *         @ORM\Index(columns={"number"})
 *     }
 * )
 */
class Car
{
    use Common\Identifiable;
    use Common\TimeTrackable;
    use Common\Stringable;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=50, nullable=true)
     */
    protected $number;

    /**
     * @var string
     *
     * @ORM\Column(name="vin", type="string", length=50, nullable=true)
     */
    protected $vin;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=50, nullable=true)
     */
    protected $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="model", type="string", length=50, nullable=true)
     */
    protected $model;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=250, nullable=true)
     */
    protected $note;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Order", inversedBy="cars")
     * @ORM\JoinColumn(name="id_order", referencedColumnName="id")
     */
    protected $order;

    /**
     * @param string $number
     * @param string $brand
     * @param string $model
     * @param string $note
     */
    public function __construct($number, $brand = null, $model = null, $note = null)
    {
        $this->number = $number;
        $this->brand  = $brand;
        $this->model  = $model;
        $this->note   = $note;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     *
     * @return self
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return trim(sprintf('%s %s', $this->brand, $this->model));
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%s:{%s,`%s %s`,`%s`}', static::class, $this->id, $this->brand, $this->model, $this->createdAt->format('Y-m-d H:i'));
    }
}
