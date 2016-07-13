<?php

namespace AppBundle\Repository\Query;

use AppBundle\Entity\Detail;
use AppBundle\Repository\Query;

class DetailAllCarbody extends Query
{
    /**
     * {@inheritdoc}
     *
     * @return Detail[]
     */
    public function execute(array $params = [])
    {
        $builder = $this->queryBuilder(Detail::class, 'd')
            ->orderBy('d.id', 'DESC')

            ->leftJoin('d.packageDetails', 'packageDetail')
            ->addSelect('packageDetail')

            ->leftJoin('packageDetail.package', 'package')
            ->addSelect('package')
        ;

        return $builder->getQuery()
                ->getResult();
    }
}
