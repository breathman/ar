<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="work_detail_package",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"id_work","id_package_detail"})
 *     }
 * )
 */
class WorkDetailPackage
{
    use Common\Identifiable;

    /**
     * @var int
     *
     * @ORM\Column(name="qty", type="float", nullable=false)
     */
    protected $qty;

    /**
     * @var Work
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Work", inversedBy="workDetailPackages", fetch="EAGER")
     * @ORM\JoinColumn(name="id_work", referencedColumnName="id")
     */
    protected $work;

    /**
     * @var PackageDetail
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PackageDetail", inversedBy="workDetailPackages")
     * @ORM\JoinColumn(name="id_package_detail", referencedColumnName="id")
     */
    protected $packageDetail;

    /**
     * Конструктор
     *
     * @param Work          $work
     * @param PackageDetail $packageDetail
     * @param float         $qty
     */
    public function __construct(Work $work, PackageDetail $packageDetail, $qty)
    {
        $this->work          = $work;
        $this->packageDetail = $packageDetail;
        $this->qty           = $qty;
    }

    /**
     * @return float
     */
    public function getQty()
    {
        return $this->qty;
    }

    /**
     * @param float $qty
     *
     * @return self
     */
    public function setQty($qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * @return PackageDetail
     */
    public function getPackageDetail()
    {
        return $this->packageDetail;
    }

    /**
     * @return Work
     */
    public function getWork()
    {
        return $this->work;
    }

    /**
     * @return float
     */
    public function getCost()
    {
        return ($this->work->getCost() * $this->qty);
    }
}
