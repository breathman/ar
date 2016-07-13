<?php

namespace AppBundle\Service;

use AppBundle\Dto\OrderEdit;
use AppBundle\Entity\Order;
use AppBundle\Event\Event;
use AppBundle\Event\OrderEvent;
use AppBundle\Exception\RuntimeException;
use AppBundle\Service\Command\OrderCreate;
use AppBundle\Service\Command\OrderUpdate;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use LogicException;

class OrderEditService
{
    /**
     * @var OrderCreate
     */
    protected $creator;

    /**
     * @var OrderUpdate
     */
    protected $updater;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param OrderCreate              $creator
     * @param OrderUpdate              $updater
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        OrderCreate              $creator,
        OrderUpdate              $updater,
        EventDispatcherInterface $dispatcher
    ) {
        $this->creator    = $creator;
        $this->updater    = $updater;
        $this->dispatcher = $dispatcher;
    }


    /**
     * @param OrderEdit $orderEdit
     *
     * @return Order
     *
     * @throws LogicException
     * @throws RuntimeException
     */
    public function edit(OrderEdit $orderEdit)
    {
        if ($orderEdit->isNew()) {
            $this->creator->execute($orderEdit);

            $order = $this->creator->getOrder();
            $event = Event::ORDER_CREATED;
        } else {
            $this->updater->execute($orderEdit);

            $order = $this->updater->getOrder();
            $event = Event::ORDER_UPDATED;
        }

        $this->dispatcher->dispatch($event, new OrderEvent($order->getId()));

        return $order;
    }
}
