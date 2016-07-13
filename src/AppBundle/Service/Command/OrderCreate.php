<?php

namespace AppBundle\Service\Command;

use AppBundle\Dto\OrderEdit;
use AppBundle\Entity\Order;
use AppBundle\Exception\RuntimeException;
use AppBundle\Service\UserKeyService;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;


class OrderCreate extends OrderUpdate
{
    /**
     * @var UserKeyService
     */
    protected $userKeyService;

    /**
     * @param int                    $worksLimit
     * @param int                    $filesLimit
     * @param UserKeyService         $userKeyService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        $worksLimit,
        $filesLimit,
        UserKeyService         $userKeyService,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct(
            $worksLimit,
            $filesLimit,
            $entityManager
        );

        $this->userKeyService = $userKeyService;
    }

    /**
     * @param OrderEdit $orderEdit
     *
     * @throws LogicException
     * @throws RuntimeException
     */
    public function execute(OrderEdit $orderEdit)
    {
        $this->order = new Order($this->userKeyService->create());
        $this->entityManager->persist($this->order);

        $this->saveOther($this->order, $orderEdit);
        $this->entityManager->refresh($this->order);
    }
}
