<?php

namespace Xavante\API\Actions\User;

use Xavante\API\Actions\BaseAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Xavante\API\DTO\BaseDTO;
use Xavante\API\DTO\User\CreateUserDTO;
use Xavante\API\Services\UserService;
use Xavante\API\Services\ConfigurationService;

class WhoAmIAction extends BaseAction
{
    protected UserService $userService;
    protected ConfigurationService $config;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->userService = $app->getContainer()->get(UserService::class);
        $this->config = $app->getContainer()->get(ConfigurationService::class);
    }


    public function __invoke(Request $request, Response $response, array $args = [])
    {
        $status = [
            'name' => $this->config->getData('CLIENT_NAME'),
            'client_id' => $this->config->getData('CLIENT_ID'),
            'permissions' => $this->config->getData('CLIENT_PERMISSIONS')
        ];



        return $this->jsonResponse($response, $status);
    }


}
