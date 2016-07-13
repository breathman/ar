<?php

namespace AppBundle\Repository\Query;

use AppBundle\Entity\CarService;
use AppBundle\Repository\Query;
use Assert\Assertion;

class CarServiceById extends Query
{
    /**
     * {@inheritdoc}
     *
     * @return CarService|null
     */
    public function execute(array $params = [])
    {
        Assertion::integerish($params['id']);

        return $this->repository(CarService::class)->find($params['id']);
    }
}
