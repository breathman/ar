<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="detail")
 */
class Detail
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PackageDetail", mappedBy="detail")
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
     * @return Package[]
     */
    public function getPackages()
    {
        return $this->packageDetails->map(function (PackageDetail $packageDetail) {
            return $packageDetail->getPackage();
        });
    }

    /**
     * @return PackageDetail[]|Collection
     */
    public function getPackageDetails()
    {
        return $this->packageDetails;
    }

    /**
     * @param Package $package
     *
     * @return self
     */
    public function addToPackage(Package $package)
    {
        $package->addDetail($this);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return sprintf('%s:{%s,`%s`}', static::class, $this->id, $this->name);
    }
}
