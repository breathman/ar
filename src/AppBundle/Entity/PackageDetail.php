<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *     name="package_detail",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"id_detail","id_package"})
 *     }
 * )
 */
class PackageDetail
{
    use Common\Identifiable;
    use Common\Stringable;

    /**
     * @var Detail
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Detail", inversedBy="packageDetails", fetch="EAGER")
     * @ORM\JoinColumn(name="id_detail", referencedColumnName="id")
     */
    protected $detail;

    /**
     * @var Package
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Package", inversedBy="packageDetails", fetch="EAGER")
     * @ORM\JoinColumn(name="id_package", referencedColumnName="id")
     */
    protected $package;

    /**
     * @var WorkDetailPackage[]|Collection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\WorkDetailPackage", mappedBy="packageDetail", cascade={"persist"})
     */
    protected $workDetailPackages;

    /**
     * Конструктор
     *
     * @param Detail  $detail
     * @param Package $package
     */
    public function __construct(Detail $detail, Package $package)
    {
        $this->detail  = $detail;
        $this->package = $package;
    }

    /**
     * @return Detail
     */
    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @return Package
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @return ArrayCollection|WorkDetailPackage[]
     */
    public function getWorkDetailPackages()
    {
        return $this->workDetailPackages;
    }

    /**
     * @param WorkDetailPackage $workDetailPackage
     *
     * @return self
     */
    public function addWorkDetailPackages(WorkDetailPackage $workDetailPackage)
    {
        $this->workDetailPackages[] = $workDetailPackage;

        return $this;
    }

    /**
     * @return float
     */
    public function getCost()
    {
        return (float) array_sum($this->workDetailPackages
            ->map(function(WorkDetailPackage $workDetailPackage) {
                return $workDetailPackage->getCost();
            })
            ->getValues()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%s:{%s}', static::class, $this->id);
    }
}
