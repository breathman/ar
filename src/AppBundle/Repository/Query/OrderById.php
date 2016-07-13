<?php

namespace AppBundle\Repository\Query;

use AppBundle\Entity\Order;
use AppBundle\Repository\Query;
use Assert\Assertion;

class OrderById extends Query
{
    /**
     * {@inheritdoc}
     *
     * @return Order|null
     */
    public function execute(array $params = [])
    {
        Assertion::integerish($params['id']);

        return $this->repository(Order::class)->find($params['id']);
    }
}
