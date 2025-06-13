<?php

namespace Xavante\API\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xavante\API\Services\AuthenticationService;

class AuthenticateAction extends BaseAction
{
    public function __invoke(Request $request, Response $response, array $args = [])
    {
        $token = $request->getHeaderLine('X-ACCESS-TOKEN');
        $check = $request->getHeaderLine('X-ACCESS-CHECK');

        try {
            $authToken = $this->authenticationService->validateAndReturnAuthCredentials($token, $check);
            $response = $response->withAddedHeader('Authorization', $authToken);
            return $this->jsonResponse($response, ['status' => 'success', 'message' => 'Authenticated successfully']);

        } catch (\Exception $e) {
            return $this->jsonResponse($response, ['status' => 'error', 'message' => $e->getMessage()], 401);
        }


    }

}
