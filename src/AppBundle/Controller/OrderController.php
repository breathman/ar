<?php

namespace AppBundle\Controller;

use AppBundle\Dto\OrderEditRequest;
use AppBundle\Service\OrderEditService;
use AppBundle\Service\OrderInfoService;
use AppBundle\Exception\RuntimeException;
use AppBundle\Service\OrderListService;
use FOS\RestBundle\Controller\Annotations\Route;
use JMS\DiExtraBundle\Annotation\Inject;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use LogicException;

class OrderController extends AbstractController
{
    /**
     * @var OrderInfoService
     *
     * @Inject("orderinfo_service")
     */
    protected $orderInfoService;

    /**
     * @var OrderEditService
     *
     * @Inject("orderedit_service")
     */
    protected $orderEditService;

    /**
     * @var OrderListService
     *
     * @Inject("orderlist_service")
     */
    protected $orderListService;

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
     * @param string $key
     *
     * @Route("/carbody/order/{key}", methods={"GET"}, requirements={"key":"\w+"})
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function getOrderAction($key)
    {
        if ($orderDto = $this->orderInfoService->getOrder($key)) {
            return $this->createResponseJson($orderDto);
        }

        throw $this->createNotFoundException();
    }

    /**
     * @Route("/carbody/order", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws LogicException
     */
    public function createOrderAction(Request $request)
    {
        $orderEdit = new OrderEditRequest($request);

        try {
            if (count($errors = $this->validator->validate($orderEdit))) {
                return $this->createResponseJson($errors, 400);
            }
        } catch (UnexpectedTypeException $exception) {
            return $this->createResponseJson([$exception->getMessage()], 400);
        }

        try {
            $order = $this->orderEditService->edit($orderEdit);

            return $orderEdit->isNew()
                ? $this->createResponseJson($order->getUserKey(), 201, [
                    'Location' => $this->generateUrl('app_order_getorder', [
                        'key' => $order->getUserKey(),
                    ]),
                ])
                : $this->createResponse('');
        } catch (RuntimeException $exception) {
            $this->logger->warning(sprintf('Ошибка заказа: `%s`', $exception->getMessage()));

            return $this->createResponseJson([$exception->getMessage()], 400);
        }
    }

    /**
     * @param string $key
     *
     * @Route("/carbody/code/{key}", methods={"GET"}, requirements={"key":"\w+"})
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function getCodeAction($key)
    {
        if ($png = $this->orderInfoService->getCode($key)) {
            return $this->createResponsePng($png);
        }

        throw $this->createNotFoundException();
    }

    /**
     * @param string  $key
     * @param Request $request
     *
     * @Route("/carbody/file/{key}", methods={"GET"}, requirements={"key":"\w+"})
     *
     * @return Response
     *
     * @throws NotFoundHttpException
     */
    public function getFileAction($key, Request $request)
    {
        if ($file = $this->orderInfoService->getFile($key)) {
            $headers = array(
                'Content-type' => $file->getMime(),
            );

            if ('dl' === $request->getQueryString()) {
                $headers['Content-disposition'] = sprintf('attachment; filename="%s"', $file->getName(true));
            }

            return $this->createResponse($file->getBody(), 200, $headers);
        }

        throw $this->createNotFoundException();
    }
}
