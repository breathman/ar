<?php

namespace AppBundle\Service;

use AppBundle\Dto\CarbodyDetail;
use AppBundle\Dto\CarbodyIdName;
use AppBundle\Dto\CarbodyPackage;
use AppBundle\Entity\Detail;
use AppBundle\Entity\PackageDetail;
use AppBundle\Repository\Query\DetailAllCarbody;
use AppBundle\Repository\Query\PackageDetailAllCarbody;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class CarbodyService
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @return CarbodyPackage[]
     */
    public function getPackages()
    {
        return (new ArrayCollection((new PackageDetailAllCarbody($this->entityManager))->execute()))
            ->map(function(PackageDetail $packageDetail) {
                return (new CarbodyPackage())
                    ->setId($packageDetail->getId())
                    ->setName($packageDetail->getPackage()->getName())
                    ->setDetail($packageDetail->getDetail()->getName())
                ;
            })
            ->getValues();
    }

    /**
     * @return CarbodyDetail[]
     */
    public function getDetails()
    {
        return (new ArrayCollection((new DetailAllCarbody($this->entityManager))->execute()))
            ->map(function(Detail $detail) {
                return (new CarbodyDetail())
                    ->setId($detail->getId())
                    ->setName($detail->getName())
                    ->setWorks($detail->getPackageDetails()
                        ->map(function(PackageDetail $packageDetail) {
                            return (new CarbodyIdName())
                                ->setId($packageDetail->getId())
                                ->setName($packageDetail->getPackage()->getName())
                            ;
                        })
                        ->getValues()
                    )
                ;
            })
            ->getValues();
    }
}
