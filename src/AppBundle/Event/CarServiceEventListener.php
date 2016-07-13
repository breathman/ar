<?php

namespace AppBundle\Event;

use AppBundle\Repository\Query\CarServiceById;
use AppBundle\Repository\Query\OrderById;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class CarServiceEventListener
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface        $logger
    ) {
        $this->entityManager = $entityManager;
        $this->logger        = $logger;
    }

    /**
     * @param CarServiceSubscribeEvent $event
     */
    public function onSubscribe(CarServiceSubscribeEvent $event)
    {
        $order      = (new OrderById($this->entityManager))     ->execute(['id' => $event->getOrderId()]);
        $carService = (new CarServiceById($this->entityManager))->execute(['id' => $event->getId()]);

        $this->logger->notice(sprintf('Подписался Автосервис %s на Заказ %s', $carService, $order));
    }
}
