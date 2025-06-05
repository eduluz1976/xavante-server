<?php

namespace Xavante\API\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xavante\API\Services\AuthenticationService;

class AuthenticateAction extends BaseAction
{

    public function __construct(private AuthenticationService $authenticationService){}

    public function __invoke(Request $request, Response $response, array $args=[])
    {
        // Authentication logic goes here
        // For example, you might check for a token in the request headers

        $token = $request->getHeaderLine('X-ACCESS-TOKEN');
        $check = $request->getHeaderLine('X-ACCESS-CHECK');

        // AuthPayloadDTO



        try {
            $authToken = $this->authenticationService->validateAndReturnAuthCredentials($token, $check);
            $response = $response->withAddedHeader('Authorization', $authToken);
            return $this->jsonResponse($response, ['status' => 'success', 'message' => 'Authenticated successfully']);

        } catch (\Exception $e) {
            return $this->jsonResponse($response, ['status' => 'error', 'message' => $e->getMessage()], 401);
        }


        // // Step 1 - extract the data from payload
        // // $authPayloadDTO = $this->authenticationService->extractPayloadData($token);

        // // $authPayloadDTO->hash

        // // Step 2 - load client data to validate request
        // // $authPayloadDTO = $this->authenticationService->get


        // // Step 3 - verify if the token is valid


        // // Step 4 - create a session token

        
        // if ($token === 'eyJjbGllbnRfaWQiOiJjRjIyd240QkZLeVZYMDJyZmNHZ3o1MUpWNzE5MkhBRGk1a3N5MHE0T3VwVmRSIiwidGltZXN0YW1wIjoxNzQ5MDA3MDIyfQ==') {
        //     return $this->jsonResponse($response, ['status' => 'success', 'message' => 'Authenticated successfully']);
        // } else {
        //     return $this->jsonResponse($response, ['status' => 'error', 'message' => 'Authentication failed'], 401);
        // }
    }

}