<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Query as DoctrineQuery;

interface QueryInterface
{
    /**
     * @param array $params
     *
     * @return []|object
     */
    public function execute(array $params = []);
}
