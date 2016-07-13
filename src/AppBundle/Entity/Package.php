<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Entity()
 * @ORM\Table(name="package")
 */
class Package
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
     * @var PackageDetail[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PackageDetail", mappedBy="package", cascade={"persist"})
     */
    protected $packageDetails;

    /**
     * Конструктор
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->packageDetails = new ArrayCollection();

        $this->name = $name;
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
     * @return Detail[]
     */
    public function getDetails()
    {
        return $this->packageDetails->map(function (PackageDetail $packageDetail) {
            return $packageDetail->getDetail();
        });
    }

    /**
     * @param Detail $detail
     *
     * @return bool
     */
    public function hasDetail(Detail $detail)
    {
        return $this->packageDetails->exists(function ($_, PackageDetail $packageDetail) use ($detail) {
            return $detail->equals($packageDetail->getDetail());
        });
    }

    /**
     * @param Detail $detail
     *
     * @return self
     */
    public function addDetail(Detail $detail)
    {
        $this->packageDetails[] = new PackageDetail($detail, $this);

        return $this;
    }

    /**
     * @param Work   $work
     * @param Detail $detail
     * @param int    $qty
     *
     * @return $this
     *
     * @throws InvalidArgumentException
     */
    public function addWork(Work $work, Detail $detail, $qty)
    {
        $packageDetail = $this->getPackageDetail($detail);
        $packageDetail->addWorkDetailPackages(new WorkDetailPackage($work, $packageDetail, $qty));

        return $this;
    }

    /**
     * @param Detail $detail
     *
     * @return Work[]
     *
     * @throws InvalidArgumentException
     */
    public function getWorks(Detail $detail)
    {
        $packageDetail = $this->getPackageDetail($detail);

        return $packageDetail->getWorkDetailPackages()
            ->map(function(WorkDetailPackage $workDetailPackage) {
                return $workDetailPackage->getWork();
            });
    }

    /**
     * @param Detail $detail
     *
     * @return float
     *
     * @throws InvalidArgumentException
     */
    public function getCost(Detail $detail)
    {
        return $this->getPackageDetail($detail)
            ->getCost();
    }

    /**
     * @param Detail $detail
     *
     * @return PackageDetail
     *
     * @throws InvalidArgumentException
     */
    public function getPackageDetail(Detail $detail)
    {
        $packageDetails = $this->packageDetails->filter(function(PackageDetail $packageDetail) use ($detail) {
            return $detail->equals($packageDetail->getDetail());
        });

        if ($packageDetails->isEmpty()) {
            throw new InvalidArgumentException(sprintf('Не найден Комплекс %s для Детали %s', $this, $detail));
        }

        return $packageDetails->first();
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%s:{%s,%s}', static::class, $this->id, $this->name);
    }
}
