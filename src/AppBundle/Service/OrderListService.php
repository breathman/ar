<?php

namespace AppBundle\Service;

use AppBundle\Dto\CarbodyPackage;
use AppBundle\Dto\OrderCar;
use AppBundle\Dto\OrderEstimate;
use AppBundle\Dto\OrderEstimates as OrderEstimatesDto;
use AppBundle\Dto\OrderFile;
use AppBundle\Dto\OrderInfo;
use AppBundle\Dto\OrderList as OrderListDto;
use AppBundle\Entity\Estimate;
use AppBundle\Entity\File;
use AppBundle\Entity\Order;
use AppBundle\Entity\PackageDetail;
use AppBundle\Entity\WorkDetailPackage;
use AppBundle\Repository\Query\OrderEstimatesCabinet;
use AppBundle\Repository\Query\OrderListCabinet;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use DateInterval;

class OrderListService
{
    /**
     * @var DateInterval
     */
    protected $interval;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param string                 $interval
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        $interval,
        EntityManagerInterface $entityManager
    ) {
        $this->interval      = new DateInterval($interval);
        $this->entityManager = $entityManager;
    }

    /**
     * @param Order[] $orders
     *
     * @return OrderInfo[]
     */
    protected function mapOrders(array $orders)
    {
        return array_map(function(Order $order) {
            return (new OrderInfo())
                ->setKey($order->getUserKey())
                ->setTime($order->getCreatedAt())
                ->setEstimates($order->getEstimates()
                    ->map(function(Estimate $estimate) {
                        $car = $estimate->getCar();

                        return (new OrderEstimate())
                            ->setCost($estimate->getCost())
                            ->setTime($estimate->getCreatedAt())
                            ->setPackages($estimate->getPackageDetails()
                                ->map(function(PackageDetail $packageDetail) {
                                    return (new CarbodyPackage())
                                        ->setId($packageDetail->getId())
                                        ->setName($packageDetail->getPackage()->getName())
                                        ->setDetail($packageDetail->getDetail()->getName())
                                        ->setWorks($packageDetail->getWorkDetailPackages()
                                            ->map(function(WorkDetailPackage $workDetailPackage) {
                                                return $workDetailPackage->getWork()->getName();
                                            })
                                            ->getValues()
                                        )
                                    ;
                                })
                                ->getValues()
                            )
                            ->setCar((new OrderCar())
                                ->setNumber($car->getNumber())
                                ->setName($car->getName())
                                ->setNote($car->getNote())
                            )
                        ;
                    })
                    ->getValues()
                )
                ->setFiles($order->getFiles()
                    ->map(function(File $file) {
                        return (new OrderFile())
                            ->setKey($file->getKey())
                            ->setTime($file->getCreatedAt())
                            ->setMime($file->getMime())
                            ->setName($file->getName())
                            ->setNote($file->getNote())
                        ;
                    })
                    ->getValues()
                )
            ;
        },
            $orders
        );
    }

    /**
     * @param OrderEstimatesDto $orderEstimates
     *
     * @return OrderInfo[]
     */
    public function getEstimates(OrderEstimatesDto $orderEstimates)
    {
        $params = array(
            'from' => (new DateTime())->sub($orderEstimates->hasInterval() ? $orderEstimates->getInterval() : $this->interval),
            'till' => (new DateTime()),
        );

        if ($orderEstimates->getKeys()) {
            $params['keys'] = $orderEstimates->getKeys();
        }

        return $this->mapOrders((new OrderEstimatesCabinet($this->entityManager))->execute($params));
    }

    /**
     * @param OrderListDto $orderList
     *
     * @return OrderInfo[]
     */
    public function getOrders(OrderListDto $orderList)
    {
        $params = array(
            'from' => (new DateTime())->sub($orderList->hasInterval() ? $orderList->getInterval() : $this->interval),
            'till' => (new DateTime()),
        );

        if ($orderList->getKeys()) {
            $params['keys'] = $orderList->getKeys();
        }

        $params['ticker'] = $orderList->getTicker();

        return $this->mapOrders((new OrderListCabinet($this->entityManager))->execute($params));
    }
}
