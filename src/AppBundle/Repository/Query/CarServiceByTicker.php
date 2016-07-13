<?php

namespace AppBundle\Repository\Query;

use AppBundle\Entity\CarService;
use AppBundle\Repository\Query;
use Assert\Assertion;

class CarServiceByTicker extends Query
{
    /**
     * {@inheritdoc}
     *
     * @return CarService|null
     */
    public function execute(array $params = [])
    {
        Assertion::string($params['key']);

        $builder = $this->queryBuilder(CarService::class, 's')
            ->where('s.ticker = :key')
            ->setParameter('key', $params['key']);

        return $builder->getQuery()
            ->getOneOrNullResult();
    }
}
