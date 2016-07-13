<?php

namespace AppBundle\Repository\Query;

use AppBundle\Entity\PackageDetail;
use AppBundle\Repository\Query;
use Assert\Assertion;

class PackageDetailById extends Query
{
    /**
     * {@inheritdoc}
     *
     * @return PackageDetail|null
     */
    public function execute(array $params = [])
    {
        Assertion::integerish($params['id']);

        return $this->repository(PackageDetail::class)->find($params['id']);
    }
}
