<?php

namespace AppBundle\Dto;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class OrderListRequest extends OrderList
{
    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct(
            $request->get('interval'),
            $request->get('key'),
            $request->get('service_key')
        );
    }
}
