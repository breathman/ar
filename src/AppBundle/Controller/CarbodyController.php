<?php

namespace AppBundle\Controller;

use AppBundle\Service\CarbodyService;
use FOS\RestBundle\Controller\Annotations\Route;
use JMS\DiExtraBundle\Annotation\Inject;
use Symfony\Component\HttpFoundation\Response;

class CarbodyController extends AbstractController
{
    /**
     * @var CarbodyService
     *
     * @Inject("carbody_service")
     */
    protected $carbodyService;

    /**
     * @Route("/carbody/packages", methods={"GET"})
     *
     * @return Response
     */
    public function packagesAction()
    {
        return $this->createResponseJson($this->carbodyService->getPackages());
    }

    /**
     * @Route("/carbody/details", methods={"GET"})
     *
     * @return Response
     */
    public function detailsAction()
    {
        return $this->createResponseJson($this->carbodyService->getDetails());
    }
}
