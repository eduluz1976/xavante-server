<?php

namespace Xavante\API\Actions;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Symfony\Component\Validator\Validation;
use Xavante\API\DTO\BaseDTO;
use Xavante\API\Repositories\Redis;
use Xavante\API\Services\AuthenticationService;
use Xavante\API\Services\ConfigurationService;
use Xavante\API\Services\UserService;

abstract class BaseAction
{
    protected AuthenticationService $authenticationService;
    protected ConfigurationService $config;
    protected Redis $redis;
    protected UserService $userService;

    public function __construct($app)
    {
        $this->authenticationService = $app->getContainer()->get(AuthenticationService::class);
        $this->config = $app->getContainer()->get(ConfigurationService::class);
        $this->redis = $app->getContainer()->get(Redis::class);
        $this->userService = $app->getContainer()->get(UserService::class);
    }

    abstract public function __invoke(Request $request, Response $response, array $args = []);


    protected function getData(Request $request): array
    {
        $data = json_decode($request->getBody()->getContents(), true);
        $userId = $this->config->getData('CLIENT_ID');
        if (!empty($userId)) {
            $data['ownerId'] = $userId;
        }
        return $data ?: [];
    }

    protected function validateRequestData(BaseDTO $requestDTO): void
    {
        $errors = [];

        if (($errors = $this->validateRequest($requestDTO)) !== false) {
            throw new \InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
        }
    }


    protected function jsonResponse(Response $response, $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $response->withStatus($status)->withHeader('Content-Type', 'application/json');
    }

    protected function validateRequest(BaseDTO $dto): array|bool
    {
        $validator = Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
        $violations = $validator->validate($dto);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $errors;
        }

        return false;
    }
}
