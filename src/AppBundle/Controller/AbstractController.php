<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController extends Controller
{
    /**
     * @param string $body
     * @param int    $code
     * @param array  $headers
     *
     * @return Response
     */
    public function createResponse($body, $code = 200, $headers = [])
    {
        return new Response($body, $code, $headers);
    }

    /**
     * @param string $string
     * @param int    $code
     * @param array  $headers
     *
     * @return Response
     */
    protected function createResponseJson($string, $code = 200, array $headers = [])
    {
        return $this->createResponse($this->get('serializer')->serialize($string, 'json'), $code, array_merge($headers, [
            'Content-type' => 'application/json',
        ]));
    }

    /**
     * @param string $png
     * @param int    $code
     * @param array  $headers
     *
     * @return Response
     */
    protected function createResponsePng($png, $code = 200, array $headers = [])
    {
        return $this->createResponse($png, $code, array_merge($headers, [
            'Content-type' => 'image/png',
        ]));
    }
}
