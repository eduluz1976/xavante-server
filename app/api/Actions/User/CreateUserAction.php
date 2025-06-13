<?php

namespace Xavante\API\Actions\User;

use Xavante\API\Actions\BaseAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Xavante\API\DTO\BaseDTO;
use Xavante\API\DTO\User\CreateUserDTO;
use Xavante\API\Services\UserService;

class CreateUserAction extends BaseAction
{
    protected UserService $userService;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->userService = $app->getContainer()->get(UserService::class);
    }

    public function __invoke(Request $request, Response $response, array $args = [])
    {

        // print_r($args); exit;

        // Extract the JSON body from the request
        $data = $this->getData($request);

        $requestDTO = $this->getDTOFromRequest($data);

        try {
            $this->validateRequestData($requestDTO);
            $createdUser = $this->userService->createUser($requestDTO);
        } catch (\InvalidArgumentException $e) {
            return $this->jsonResponse($response, ['error' => 'Validation failed: ' . $e->getMessage()], 400);
        } catch (\RuntimeException $e) {
            return $this->jsonResponse($response, ['error' => 'Workflow creation failed: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return $this->jsonResponse($response, ['error' => 'Failed to create workflow: ' . $e->getMessage()], 500);
        }

        return $this->jsonResponse($response, [
            'id' => $createdUser->id,
            'client_id' => $createdUser->client_id,
            'secret' => $createdUser->secret
        ], 201);
    }


    protected function getDTOFromRequest(array $data): BaseDTO
    {
        return new CreateUserDTO($data);
    }

}
