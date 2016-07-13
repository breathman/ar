<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Entity()
 * @ORM\Table(name="work")
 */
class Work
{
    use Common\Identifiable;
    use Common\Stringable;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=250, unique=true, nullable=false)
     */
    protected $name;

    /**
     * @var int
     *
     * @ORM\Column(name="cost", type="integer", nullable=false)
     */
    protected $cost;

    /**
     * @var WorkDetailPackage[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkDetailPackage", mappedBy="work")
     */
    protected $workDetailPackages;

    /**
     * Конструктор
     *
     * @param string $name
     * @param int    $cost
     */
    public function __construct($name, $cost)
    {
        $this->workDetailPackages = new ArrayCollection();

        $this->name = $name;
        $this->cost = $cost;
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
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @param int $cost
     *
     * @return self
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @param Detail  $detail
     * @param Package $package
     *
     * @return float
     *
     * @throws InvalidArgumentException
     */
    public function getQty(Detail $detail, Package $package)
    {
        $packageDetail = $package->getPackageDetail($detail);

        $workDetailPackages = $this->workDetailPackages->filter(function(WorkDetailPackage $workDetailPackage) use ($packageDetail) {
            return $packageDetail->equals($workDetailPackage->getPackageDetail());
        });

        if ($workDetailPackages->isEmpty()) {
            throw new InvalidArgumentException(sprintf('Не найдена Работа %s в Комплексе %s для Детали %s', $this, $package, $detail));
        }

        /** @var $workDetailPackage WorkDetailPackage */
        $workDetailPackage = $workDetailPackages->first();

        return $workDetailPackage->getQty();
    }

    /**
     * @param Package $package
     * @param Detail  $detail
     * @param float   $qty
     *
     * @return self
     *
     * @throws InvalidArgumentException
     */
    public function addToPackage(Package $package, Detail $detail, $qty)
    {
        $package->addWork($this, $detail, $qty);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%s:{%s,%s,`%s`}', static::class, $this->id, $this->cost, $this->name);
    }
}
