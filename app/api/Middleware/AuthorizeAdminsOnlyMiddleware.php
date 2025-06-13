<?php

namespace Xavante\API\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use RuntimeException;
use Xavante\API\Helpers\AuthHelper;
use Xavante\API\Repositories\Redis;
use Xavante\API\Services\AuthenticationService;
use Xavante\API\Services\ConfigurationService;
use Xavante\API\Services\UserService;

class AuthorizeAdminsOnlyMiddleware
{
    use AuthHelper;

    protected AuthenticationService $authenticationService;
    protected ResponseFactoryInterface $responseFactory;
    protected Redis $redis;
    protected UserService $userService;
    protected ConfigurationService $config;


    public function __construct($container)
    {
        $this->authenticationService = $container->get(AuthenticationService::class);
        $this->responseFactory = $container->get(ResponseFactoryInterface::class);
        $this->redis = $container->get(Redis::class);
        $this->userService = $container->get(UserService::class);
        $this->config = $container->get(ConfigurationService::class);
    }


    /**
     * Example middleware closure
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(Request $request, RequestHandler $handler): Response
    {

        try {
            $this->authorize($request);
        } catch (RuntimeException $ex) {
            $response = $this->responseFactory->createResponse(403, 'Forbidden');
            $json = ['message' => 'Error: ' . $ex->getMessage()];
            $response->getBody()->write(json_encode($json));
            $response = $response->withStatus(403);
            $response = $response->withAddedHeader('Content-Type', 'application/json');
            return $response;
        }

        return $handler->handle($request);
    }



    protected function authorize(Request $request)
    {


        $permissions = $this->config->getData('CLIENT_PERMISSIONS') ?? [];

        // print_r($permissions); exit;
        $isAuthorized = false;

        foreach ($permissions as $permission) {
            if (!isset($permission['role'])) {
                continue;
            }
            if (strtolower($permission['role']) === 'admin') {
                $isAuthorized = true;
            }
        }

        if (!$isAuthorized) {
            throw new RuntimeException("User not authorized");
        }
    }

}
