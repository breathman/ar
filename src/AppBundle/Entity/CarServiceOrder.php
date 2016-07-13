<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="carservice_order",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"id_carservice","id_order"})
 *     }
 * )
 */
class CarServiceOrder
{
    use Common\Identifiable;
    use Common\TimeTrackable;

    /**
     * @var CarService
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CarService")
     * @ORM\JoinColumn(name="id_carservice", referencedColumnName="id")
     */
    protected $carService;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Order")
     * @ORM\JoinColumn(name="id_order", referencedColumnName="id")
     */
    protected $order;

    /**
     * @var int
     *
     * @ORM\Column(name="cost", type="integer", nullable=true)
     */
    protected $cost;

    /**
     * @var OrderNote[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\OrderNote", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="carservice_order_order_note",
     *     joinColumns={@ORM\JoinColumn(name="id_carservice_order", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="id_order_note", referencedColumnName="id")}
     * )
     * @ORM\OrderBy({"id"="DESC"})
     */
    protected $notes;

    /**
     * @param CarService $carService
     * @param Order      $order
     */
    public function __construct(CarService $carService, Order $order)
    {
        $this->carService = $carService;
        $this->order      = $order;

        $this->notes      = new ArrayCollection();
    }

    /**
     * @return CarService
     */
    public function getCarService()
    {
        return $this->carService;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param int $cost
     *
     * @return $this
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param OrderNote $note
     *
     * @return $this
     */
    public function addNote(OrderNote $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * @return OrderNote[]|Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%s:{`%s`}', static::class, $this->createdAt->format('Y-m-d H:i'));
    }
}
