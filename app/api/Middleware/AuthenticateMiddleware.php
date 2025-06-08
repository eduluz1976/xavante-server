<?php

namespace Xavante\API\Middleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;

use RuntimeException;
use Xavante\API\Services\AuthenticationService;

class AuthenticateMiddleware {


    protected AuthenticationService $authenticationService;
    protected ResponseFactoryInterface $responseFactory;


    public function __construct($container)
    {
        $this->authenticationService = $container->get(AuthenticationService::class);
        $this->responseFactory = $container->get(ResponseFactoryInterface::class);
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
    public function __invoke(Request $request, RequestHandler $handler) : Response
    {

        try {
            $this->authenticate($request);
        } catch (RuntimeException $ex) {
            $response = $this->responseFactory->createResponse(403, 'Forbidden');
            $response->getBody()->write('Error: ' . $ex->getMessage());    
            return $response;
        }

        return $handler->handle($request);
    }



    protected function authenticate(Request $request)
    {
        $authorizationHeader = $request->getHeader('Authorization');
        if (empty($authorizationHeader)) {
            throw new RuntimeException("Auth token is not present");
        }

        $authorizationHeader = (string) $authorizationHeader[0];
        
        if (strlen($authorizationHeader) < 10) {
            throw new RuntimeException("Invalid auth token");
        }

        $token = substr($authorizationHeader,7);
        $this->authenticationService->verifyAuthToken($token);
    }    

}