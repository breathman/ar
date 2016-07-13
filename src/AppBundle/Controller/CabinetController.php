<?php

namespace AppBundle\Controller;

use AppBundle\Dto\OrderEstimatesRequest;
use AppBundle\Dto\OrderListRequest;
use AppBundle\Dto\OrderSubscribeRequest;
use AppBundle\Exception\RuntimeException;
use AppBundle\Service\OrderListService;
use AppBundle\Service\OrderSubscribeService;
use FOS\RestBundle\Controller\Annotations\Route;
use JMS\DiExtraBundle\Annotation\Inject;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CabinetController extends AbstractController
{
    /**
     * @var OrderListService
     *
     * @Inject("orderlist_service")
     */
    protected $orderListService;

    /**
     * @var OrderSubscribeService
     *
     * @Inject("ordersubscribe_service")
     */
    protected $orderSubscribeService;

    /**
     * @var ValidatorInterface
     *
     * @Inject("validator")
     */
    protected $validator;

    /**
     * @var LoggerInterface
     *
     * @Inject("logger")
     */
    protected $logger;

    /**
     * @Route("/cabinet/estimates", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getEstimatesAction(Request $request)
    {
        $orderEstimates = new OrderEstimatesRequest($request);

        try {
            if (count($errors = $this->validator->validate($orderEstimates))) {
                return $this->createResponseJson($errors, 400);
            }
        } catch (UnexpectedTypeException $exception) {
            return $this->createResponseJson([$exception->getMessage()], 400);
        }

        return $this->createResponseJson($this->orderListService->getEstimates($orderEstimates));
    }

    /**
     * @Route("/cabinet/subscribe", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function subscribeOrderAction(Request $request)
    {
        $orderSubscribe = new OrderSubscribeRequest($request);

        try {
            if (count($errors = $this->validator->validate($orderSubscribe))) {
                return $this->createResponseJson($errors, 400);
            }
        } catch (UnexpectedTypeException $exception) {
            return $this->createResponseJson([$exception->getMessage()], 400);
        }

        try {
            $this->orderSubscribeService->subscribe($orderSubscribe);
            return $this->createResponseJson('');
        } catch (RuntimeException $exception) {
            $this->logger->warning(sprintf('Ошибка заказа: `%s`', $exception->getMessage()));

            return $this->createResponseJson([$exception->getMessage()], 400);
        }
    }

    /**
     * @Route("/cabinet/orders", methods={"GET"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getOrdersAction(Request $request)
    {
        $orderList = new OrderListRequest($request);

        try {
            if (count($errors = $this->validator->validate($orderList))) {
                return $this->createResponseJson($errors, 400);
            }
        } catch (UnexpectedTypeException $exception) {
            return $this->createResponseJson([$exception->getMessage()], 400);
        }

        return $this->createResponseJson($this->orderListService->getOrders($orderList));
    }
}
