<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="estimate")
 */
class Estimate
{
    use Common\Identifiable;
    use Common\TimeTrackable;
    use Common\Stringable;

    /**
     * @var float
     *
     * @ORM\Column(name="cost", type="float", nullable=false)
     */
    protected $cost;

    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Order", inversedBy="estimates")
     * @ORM\JoinColumn(name="id_order", referencedColumnName="id")
     */
    protected $order;

    /**
     * @var Car
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Car")
     * @ORM\JoinColumn(name="id_car", referencedColumnName="id")
     */
    protected $car;

    /**
     * @var PackageDetail[]|Collection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\PackageDetail")
     * @ORM\JoinTable(
     *     name="estimate_package_detail",
     *     joinColumns={@ORM\JoinColumn(name="id_estimate", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="id_package_detail", referencedColumnName="id")}
     * )
     */
    protected $packageDetails;

    /**
     * @param PackageDetail[] $packageDetails
     */
    public function __construct(array $packageDetails)
    {
        $this->packageDetails = new ArrayCollection();

        array_walk($packageDetails, function(PackageDetail $packageDetail) {
            $this->packageDetails[] = $packageDetail;
        });

        $this->setCost();
    }

    /**
     * @return PackageDetail[]|Collection
     */
    public function getPackageDetails()
    {
        return $this->packageDetails;
    }

    /**
     * @param PackageDetail $packageDetail
     *
     * @return $this
     */
    public function addPackageDetail(PackageDetail $packageDetail)
    {
        $this->packageDetails[] = $packageDetail;

        return $this;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     *
     * @return self
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return Car
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param Car $car
     *
     * @return self
     */
    public function setCar(Car $car)
    {
        $this->car = $car;

        return $this;
    }

    /**
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @return float
     */
    public function setCost()
    {
        $this->cost = (float) array_sum($this->packageDetails
            ->map(function(PackageDetail $packageDetail) {
                return $packageDetail->getCost();
            })
            ->getValues()
        );

        return $this->cost;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return sprintf('%s: {%s, %s, %s}', static::class, $this->id, $this->cost, $this->createdAt->format('Y:m:d H:i'));
    }
}
