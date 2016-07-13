<?php

namespace AppBundle\Service\Command;

use AppBundle\Dto\OrderSubscribe as OrderSubscribeDto;
use AppBundle\Entity\CarService;
use AppBundle\Entity\Order;
use AppBundle\Entity\OrderNote;
use AppBundle\Exception\RuntimeException;
use AppBundle\Repository\Query\CarServiceByTicker;
use AppBundle\Repository\Query\OrderByUserKey;
use Doctrine\ORM\EntityManagerInterface;

class OrderSubscribe
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var CarService
     */
    protected $carService;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param OrderSubscribeDto $subscribeDto
     *
     * @throws RuntimeException
     */
    public function subscribe(OrderSubscribeDto $subscribeDto)
    {
        if (! $this->carService = (new CarServiceByTicker($this->entityManager))->execute(array('key' => $subscribeDto->getServiceKey()))) {
            throw new RuntimeException(sprintf('Нет такого Автосервиса `%s`', $subscribeDto->getServiceKey()));
        }

        $orderQuery = (new OrderByUserKey($this->entityManager))
            ->addDetails();

        if (! $this->order = $orderQuery->execute(array('key' => $subscribeDto->getOrderKey()))) {
            throw new RuntimeException(sprintf('Нет такого Заказа `%s`', $subscribeDto->getOrderKey()));
        }

        if (! $this->order->canSubscribedOn()) {
            throw new RuntimeException(sprintf('Нельзя подписываться на Заказ в статусе `%s`', $this->order->getStatus()));
        }

        if (! $this->carService->canSubscribe($this->order)) {
            throw new RuntimeException(sprintf('Уже подписан Автосервис `%s` на Заказ `%s`', $subscribeDto->getServiceKey(), $subscribeDto->getOrderKey()));
        }

        $subscribe = $this->carService->subscribe($this->order);

        if ($subscribeDto->hasCost()) {
            $subscribe->setCost($subscribeDto->getCost());
        }

        if ($subscribeDto->hasNote()) {
            $note = new OrderNote($subscribeDto->getNote());

            $this->order->addNote($note);
            $subscribe->addNote($note);
        }

        $this->entityManager->flush();
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return CarService
     */
    public function getCarService()
    {
        return $this->carService;
    }
}
