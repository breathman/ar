<?php

namespace AppBundle\Service\Command;

use AppBundle\Dto\OrderEdit;
use AppBundle\Entity\Car;
use AppBundle\Entity\Contact;
use AppBundle\Entity\ContactType;
use AppBundle\Entity\Estimate;
use AppBundle\Entity\File;
use AppBundle\Entity\Order;
use AppBundle\Entity\OrderNote;
use AppBundle\Exception\RuntimeException;
use AppBundle\Repository\Query\OrderByUserKey;
use AppBundle\Repository\Query\PackageDetailById;
use Doctrine\ORM\EntityManagerInterface;
use SplFileInfo;
use LogicException;


class OrderUpdate
{
    /**
     * @var int
     */
    protected $worksLimit;

    /**
     * @var int
     */
    protected $filesLimit;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @param int                    $worksLimit
     * @param int                    $filesLimit
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        $worksLimit,
        $filesLimit,
        EntityManagerInterface $entityManager
    ) {
        $this->worksLimit    = $worksLimit;
        $this->filesLimit    = $filesLimit;
        $this->entityManager = $entityManager;
    }

    /**
     * @param OrderEdit $orderEdit
     *
     * @throws LogicException
     * @throws RuntimeException
     */
    public function execute(OrderEdit $orderEdit)
    {
        $query = (new OrderByUserKey($this->entityManager))
            ->addEstimates()
            ->addDetails();

        if (! $this->order = $query->execute(['key' => $orderEdit->getKey()])) {
            throw new RuntimeException(sprintf('Не существует такого заказа `%s`', $orderEdit->getKey()));
        }

        if (! $this->order->canEdit()) {
            throw new RuntimeException(sprintf('Нельзя редактировать заказ в статусе `%s`', $this->order->getStatus()));
        }

        $this->saveOther($this->order, $orderEdit);

        $this->entityManager->refresh($this->order);
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order     $order
     * @param OrderEdit $orderEdit
     *
     * @throws RuntimeException
     */
    protected function saveOther(Order $order, OrderEdit $orderEdit)
    {
        if ($orderEdit->getNote()) {
            $order->addNote(new OrderNote($orderEdit->getNote()));
        }

        if ($orderEdit->hasWorks()) {
            if ($orderEdit->isWorksOverLimit($this->worksLimit)) {
                throw new RuntimeException(sprintf('Нельзя задавать более %s работ', $this->worksLimit));
            }

            $car = new Car($orderEdit->getCarNumber(), $orderEdit->getCarBrand(), $orderEdit->getCarModel());
            $order->addCar($car);

            $query = new PackageDetailById($this->entityManager);

            $order->addEstimate($car, new Estimate($orderEdit->mapWorks(function($work) use ($query) {
                if (! $packageDetail = $query->execute(['id' => $work])) {
                    throw new RuntimeException(sprintf('Не существует такой работы `%s`', $work));
                }
                return $packageDetail;
            })));

            $order->estimate();
        } else {
            if ($orderEdit->hasCar()) {
                $order->addCar(new Car($orderEdit->getCarNumber(), $orderEdit->getCarBrand(), $orderEdit->getCarModel()));
            }
        }

        if ($orderEdit->getPhone()) {
            $order->addContact(new Contact(ContactType::PHONE(), $orderEdit->getPhone(), $orderEdit->getName()));
        }

        if ($orderEdit->getEmail()) {
            $order->addContact(new Contact(ContactType::EMAIL(), $orderEdit->getEmail(), $orderEdit->getName()));
        }

        if ($orderEdit->hasFiles()) {
            if ($orderEdit->isFilesOverLimit($this->filesLimit)) {
                throw new RuntimeException(sprintf('Нельзя загружать более %s файлов', $this->filesLimit));
            }

            $order->addDirectory('/photos', $orderEdit->mapFiles(function(SplFileInfo $file, $mime, $name) {
                return new File(file_get_contents($file->getRealPath()), $file->getSize(), $mime, $name);
            }));
        }

        $this->entityManager->flush();
    }
}
