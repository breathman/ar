<?php

namespace AppBundle\Dto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class OrderSubscribeRequest extends OrderSubscribe
{
    /**
     * @param Request $request
     */
    public function __construct(Request $request) {
        parent::__construct(
            $request->get('order_key'),
            $request->get('service_key'),
            $request->get('cost'),
            $request->get('note')
        );
    }
}
