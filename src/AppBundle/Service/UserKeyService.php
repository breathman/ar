<?php

namespace AppBundle\Service;

use AppBundle\Entity\Order;
use AppBundle\Repository\Query\OrderByUserKey;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Psr\Log\LoggerInterface;

/**
 * Сервис ключей
 */
class UserKeyService
{
    /**
     * @var int
     */
    protected $generateAttempts;

    /**
     * @var KeyGenerator
     */
    protected $keyGenerator;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param int                    $generateAttempts
     * @param KeyGenerator           $keyGenerator
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     */
    public function __construct(
        $generateAttempts,
        KeyGenerator           $keyGenerator,
        EntityManagerInterface $entityManager,
        LoggerInterface        $logger
    ) {
        $this->generateAttempts = $generateAttempts;
        $this->keyGenerator     = $keyGenerator;
        $this->entityManager    = $entityManager;
        $this->logger           = $logger;
    }

    /**
     * @return string
     *
     * @throws LogicException
     */
    public function create()
    {
        $attempts = 0;
        while ($this->exists($userKey = $this->keyGenerator->generate())) {
            $attempts++;
            $this->logger->warning('Совпал новый пользовательский ключ с уже существующим');

            if ($attempts > $this->generateAttempts) {
                throw new LogicException('Закончились пользовательские ключи');
            }
        }

        return $userKey;
    }

    /**
     * @param string $userKey
     *
     * @return Order
     */
    public function exists($userKey)
    {
        return (new OrderByUserKey($this->entityManager))->execute(['key' => $userKey]);
    }
}
