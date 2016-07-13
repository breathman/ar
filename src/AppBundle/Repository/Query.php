<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query as DoctrineQuery;

abstract class Query implements QueryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return QueryBuilder
     */
    protected function createQueryBuilder()
    {
        return $this->entityManager->createQueryBuilder();
    }

    /**
     * @param string $className
     * @param string $alias
     *
     * @return QueryBuilder
     */
    protected function queryBuilder($className, $alias)
    {
        return $this->entityManager->createQueryBuilder()
            ->from($className, $alias)
            ->select($alias)
        ;
    }

    /**
     * @param string $dql
     *
     * @return DoctrineQuery
     */
    protected function query($dql = '')
    {
        return $this->entityManager->createQuery($dql);
    }

    /**
     * @param $className
     *
     * @return ObjectRepository
     */
    protected function repository($className)
    {
        return $this->entityManager->getRepository($className);
    }

    /**
     * @param array $params
     *
     * @return []|object
     */
    abstract public function execute(array $params = []);
}
