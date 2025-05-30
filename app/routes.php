<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;




$app->post('/api/v1/auth', function (Request $request, Response $response, array $args = []) {
     return (new \Xavante\API\Actions\Authenticate())($request, $response, $args);
});


$app->get('/api/v1/status', function (Request $request, Response $response, array $args = []) {
     return (new \Xavante\API\Actions\Status())($request, $response, $args);
});