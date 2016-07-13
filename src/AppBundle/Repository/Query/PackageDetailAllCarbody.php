<?php

namespace AppBundle\Repository\Query;

use AppBundle\Entity\PackageDetail;
use AppBundle\Repository\Query;

class PackageDetailAllCarbody extends Query
{
    /**
     * {@inheritdoc}
     *
     * @return PackageDetail[]
     */
    public function execute(array $params = [])
    {
        $builder = $this->queryBuilder(PackageDetail::class, 'pd')
            ->orderBy('pd.id', 'DESC')

            ->innerJoin('pd.detail', 'detail')
            ->addSelect('detail')

            ->leftJoin('pd.package', 'package')
            ->addSelect('package')
        ;

        return $builder->getQuery()
            ->getResult();
    }
}
