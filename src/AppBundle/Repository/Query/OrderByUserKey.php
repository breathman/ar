<?php

namespace AppBundle\Repository\Query;

use AppBundle\Entity\Order;
use AppBundle\Repository\Query;
use Assert\Assertion;

class OrderByUserKey extends Query
{
    /**
     * @var bool
     */
    protected $details;

    /**
     * @var bool
     */
    protected $estimates;

    /**
     * @var bool
     */
    protected $subscribes;

    /**
     * @return $this
     */
    public function addDetails()
    {
        $this->details = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function addEstimates()
    {
        $this->estimates = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function addSubscribes()
    {
        $this->subscribes = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return Order|null
     */
    public function execute(array $params = [])
    {
        Assertion::string($params['key']);

        $builder = $this->queryBuilder(Order::class, 'o')
            ->where('o.userKey = :key')
            ->setParameter('key', $params['key'])
        ;

        if ($this->details) {
            $builder
                ->leftJoin('o.cars', 'car')
                ->addSelect('car')

                ->leftJoin('o.files', 'file')
                ->addSelect('file')

                ->leftJoin('o.notes', 'note')
                ->addSelect('note')

                ->leftJoin('o.contacts', 'contacts')
                ->addSelect('contacts')
            ;
        }

        if ($this->estimates) {
            $builder
                ->leftJoin('o.estimates', 'estimate')
                ->addSelect('estimate')

                ->leftJoin('estimate.packageDetails', 'packageDetail')
                ->addSelect('packageDetail')

                ->leftJoin('packageDetail.detail', 'detail')
                ->addSelect('detail')

                ->leftJoin('packageDetail.package', 'package')
                ->addSelect('package')

                ->leftJoin('packageDetail.workDetailPackages', 'workDetailPackage')
                ->addSelect('workDetailPackage')

                ->leftJoin('workDetailPackage.work', 'work')
                ->addSelect('work')
            ;
        }

        if ($this->subscribes) {
            $builder
                ->leftJoin('o.carServiceOrders', 'carServiceOrder')
                ->addSelect('carServiceOrder')

                ->leftJoin('carServiceOrder.carService', 'carService')
                ->addSelect('carService')
            ;
        }

        return $builder->getQuery()
            ->getOneOrNullResult();
    }
}
