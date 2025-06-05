<?php

namespace Xavante\API\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class StatusAction extends BaseAction
{
    public function __invoke(Request $request, Response $response, array $args=[])
    {
        $status = [
            'status' => 'ok',
            'version' => '1.0.0',
            'timestamp' => date('Y-m-d H:i:s')
        ];

        return $this->jsonResponse($response, $status);
    }
}