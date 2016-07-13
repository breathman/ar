<?php

namespace AppBundle\Event;

use AppBundle\Repository\Query\OrderById;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class OrderEventListener
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
     * @param OrderEvent $event
     */
    public function onCreate(OrderEvent $event)
    {
        $order = (new OrderById($this->entityManager))->execute(['id' => $event->getId()]);

        $this->logger->notice(sprintf('Новый Заказ %s', $order));
    }

    /**
     * @param OrderEvent $event
     */
    public function onUpdate(OrderEvent $event)
    {
        $order = (new OrderById($this->entityManager))->execute(['id' => $event->getId()]);

        $this->logger->notice(sprintf('Изменён Заказ %s', $order));
    }

    /**
     * @param OrderEvent $event
     */
    public function onSubscribedOn(OrderEvent $event)
    {
        $order = (new OrderById($this->entityManager))->execute(['id' => $event->getId()]);

        $this->logger->notice(sprintf('Подписка на Заказ %s', $order));
    }
}
