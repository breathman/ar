<?php

namespace AppBundle\Service;

use AppBundle\Dto\OrderSubscribe as OrderSubscribeDto;
use AppBundle\Event\CarServiceSubscribeEvent;
use AppBundle\Event\Event;
use AppBundle\Event\OrderEvent;
use AppBundle\Exception\RuntimeException;
use AppBundle\Service\Command\OrderSubscribe;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OrderSubscribeService
{
    /**
     * @var OrderSubscribe
     */
    protected $subscriber;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param OrderSubscribe           $subscriber
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        OrderSubscribe           $subscriber,
        EventDispatcherInterface $dispatcher
    ) {
        $this->subscriber = $subscriber;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param OrderSubscribeDto $subscribe
     *
     * @throws RuntimeException
     */
    public function subscribe(OrderSubscribeDto $subscribe)
    {
        $this->subscriber->subscribe($subscribe);

        $order = $this->subscriber->getOrder();
        $this->dispatcher->dispatch(Event::ORDER_SUBSCRIBED_ON, new OrderEvent($order->getId()));

        $carService = $this->subscriber->getCarService();
        $this->dispatcher->dispatch(Event::CARSERVICE_SUBSCRIBE, new CarServiceSubscribeEvent($carService->getId(), $order->getId()));
    }
}
