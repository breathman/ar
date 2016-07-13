<?php

namespace AppBundle\Service;

use AppBundle\Dto\CarbodyCarService;
use AppBundle\Dto\CarbodyPackage;
use AppBundle\Dto\CarbodySubscribe;
use AppBundle\Dto\OrderCar;
use AppBundle\Dto\OrderContact;
use AppBundle\Dto\OrderEstimate;
use AppBundle\Dto\OrderFile;
use AppBundle\Dto\OrderInfo;
use AppBundle\Dto\OrderNote as OrderNoteDto;
use AppBundle\Entity\CarServiceOrder;
use AppBundle\Entity\Contact;
use AppBundle\Entity\Estimate;
use AppBundle\Entity\File;
use AppBundle\Entity\OrderNote;
use AppBundle\Entity\PackageDetail;
use AppBundle\Entity\WorkDetailPackage;
use AppBundle\Repository\Query\FileById;
use AppBundle\Repository\Query\OrderByUserKey;
use Doctrine\ORM\EntityManagerInterface;

class OrderInfoService
{
    /**
     * @var CodeGenerator
     */
    protected $codeGenerator;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     * @param CodeGenerator          $codeGenerator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        CodeGenerator          $codeGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->codeGenerator = $codeGenerator;
    }

    /**
     * @param string $userKey
     *
     * @return OrderInfo[]
     */
    public function getOrder($userKey)
    {
        $query = (new OrderByUserKey($this->entityManager))
            ->addSubscribes()
            ->addEstimates()
            ->addDetails()
        ;

        if ($order = $query->execute(['key' => $userKey])) {
            $orderInfo = (new OrderInfo())
                ->setKey($order->getUserKey())
                ->setTime($order->getCreatedAt())
                ->setNotes($order->getNotes()
                    ->map(function(OrderNote $note) {
                        return (new OrderNoteDto())
                            ->setTime($note->getCreatedAt())
                            ->setBody($note->getBody())
                        ;
                    })
                    ->getValues()
                )
                ->setContacts($order->getContacts()
                    ->map(function(Contact $contact) {
                        return (new OrderContact())
                            ->setId($contact->getValue())
                            ->setType($contact->getType())
                            ->setName($contact->getName())
                            ->setNote($contact->getNote())
                        ;
                    })
                    ->getValues()
                )
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
                ->setSubscribes($order->getCarServiceOrders()
                    ->map(function(CarServiceOrder $carServiceOrder) {
                        $carService = $carServiceOrder->getCarService();

                        return (new CarbodySubscribe())
                            ->setTime($carServiceOrder->getCreatedAt())
                            ->setCost($carServiceOrder->getCost())
                            ->setCarService((new CarbodyCarService())
                                ->setTicker($carService->getTicker())
                                ->setName($carService->getName())
                            )
                            ->setNotes($carServiceOrder->getNotes()
                                 ->map(function(OrderNote $note) {
                                     return (new OrderNoteDto())
                                         ->setTime($note->getCreatedAt())
                                         ->setBody($note->getBody())
                                     ;
                                 })
                                 ->getValues()
                            )
                        ;
                    })
                    ->getValues()
                )
            ;

            return $orderInfo;
        }

        return null;
    }

    /**
     * @param string $userKey
     *
     * @return string
     */
    public function getCode($userKey)
    {
        if ($order = (new OrderByUserKey($this->entityManager))->execute(['key' => $userKey])) {
            return $this->codeGenerator->generate($order->getUserKey());
        }

        return null;
    }

    /**
     * @param string $key
     *
     * @return File
     */
    public function getFile($key)
    {
        return (new FileById($this->entityManager))->execute(['key' => $key]);
    }
}
