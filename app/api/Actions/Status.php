<?php

namespace Xavante\API\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class Status extends Base
{
    public function __invoke(Request $request, Response $response, $args)
    {
        $status = [
            'status' => 'ok',
            'version' => '1.0.0',
            'timestamp' => date('Y-m-d H:i:s')
        ];

        return $this->jsonResponse($response, $status);
    }
}