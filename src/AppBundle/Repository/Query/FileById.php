<?php

namespace AppBundle\Repository\Query;

use AppBundle\Entity\File;
use AppBundle\Repository\Query;
use Assert\Assertion;

class FileById extends Query
{
    /**
     * {@inheritdoc}
     *
     * @return File|null
     */
    public function execute(array $params = [])
    {
        Assertion::string($params['key']);

        return $this->repository(File::class)->findOneByKey($params['key']);
    }
}
