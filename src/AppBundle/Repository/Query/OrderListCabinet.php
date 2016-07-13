<?php

namespace AppBundle\Repository\Query;

use AppBundle\Entity\Order;
use AppBundle\Repository\Query;
use Assert\Assertion;
use DateTime;

class OrderListCabinet extends Query
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
            ->select('o', 'estimate', 'file', 'car', 'packageDetail', 'detail', 'package', 'workDetailPackage', 'work', 'carServiceOrder', 'carService')

            ->leftJoin('o.files',     'file')
            ->leftJoin('o.estimates', 'estimate')

            ->leftJoin('estimate.car',            'car')
            ->leftJoin('estimate.packageDetails', 'packageDetail')

            ->leftJoin('packageDetail.detail',             'detail')
            ->leftJoin('packageDetail.package',            'package')
            ->leftJoin('packageDetail.workDetailPackages', 'workDetailPackage')

            ->leftJoin('workDetailPackage.work', 'work')

            ->innerJoin('o.carServiceOrders',         'carServiceOrder')
            ->innerJoin('carServiceOrder.carService', 'carService')
        ;

        Assertion::string($params['ticker']);
        
        $builder
            ->andWhere('carService.ticker = :ticker')
            ->setParameter('ticker', $params['ticker']);

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
