<?php

namespace AppBundle\Repository\Query;

use AppBundle\Entity\CarService;
use AppBundle\Repository\Query;

class CarServiceAll extends Query
{
    /**
     * {@inheritdoc}
     *
     * @return CarService[]
     */
    public function execute(array $params = [])
    {
        return $this->repository(CarService::class)->findAll([], ['id' => 'ASC']);
    }
}
