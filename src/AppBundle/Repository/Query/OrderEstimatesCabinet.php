<?php

namespace AppBundle\Repository\Query;

use AppBundle\Entity\Order;
use AppBundle\Entity\OrderStatus;
use AppBundle\Repository\Query;
use Assert\Assertion;
use DateTime;

class OrderEstimatesCabinet extends Query
{
    /**
     * {@inheritdoc}
     *
     * @return Order[]
     */
    public function execute(array $params = [])
    {
        $builder = $this->createQueryBuilder()
            ->from(Order::class, 'o')
            ->select('o', 'estimate', 'file', 'car', 'packageDetail', 'detail', 'package', 'workDetailPackage', 'work')

            ->leftJoin('o.files',     'file')
            ->leftJoin('o.estimates', 'estimate')

            ->leftJoin('estimate.car',            'car')
            ->leftJoin('estimate.packageDetails', 'packageDetail')

            ->leftJoin('packageDetail.detail',             'detail')
            ->leftJoin('packageDetail.package',            'package')
            ->leftJoin('packageDetail.workDetailPackages', 'workDetailPackage')

            ->leftJoin('workDetailPackage.work', 'work')
        ;

        $builder
            ->where('o.status IN (:status)')
            ->setParameter('status', OrderStatus::ESTIMATE);

        if (array_key_exists('from', $params)) {
            Assertion::isInstanceOf($params['from'], DateTime::class);

            $builder
                ->andWhere('o.createdAt >= :from')
                ->setParameter('from', $params['from']);
        }

        if (array_key_exists('till', $params)) {
            Assertion::isInstanceOf($params['till'], DateTime::class);

            $builder
                ->andWhere('o.createdAt <= :till')
                ->setParameter('till', $params['till']);
        }

        if (array_key_exists('keys', $params)) {
            Assertion::allString($params['keys']);

            $builder
                ->andWhere('o.userKey IN (:keys)')
                ->setParameter('keys', $params['keys']);
        }

        $builder->orderBy('o.createdAt', 'DESC');

        return $builder->getQuery()
            ->getResult();
    }
}
