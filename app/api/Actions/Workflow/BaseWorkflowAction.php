<?php

namespace Xavante\API\Actions\Workflow;

use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use RuntimeException;
use \Xavante\API\Actions\BaseAction;
use Xavante\API\Actions\AuthenticateAction;
use Xavante\API\DTO\Workflow\CreateWorkflowRequestDTO;
use Xavante\API\DTO\BaseDTO;
use Xavante\API\Services\AuthenticationService;
use Xavante\API\Services\WorkflowService;

abstract class BaseWorkflowAction extends BaseAction
{
    protected  WorkflowService $workflowService;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->workflowService = $app->getContainer()->get(WorkflowService::class);
    }


    protected function getDTOFromRequest(array $data): BaseDTO
    {
        return new CreateWorkflowRequestDTO($data);
    }


    // protected function authenticate(Request $request)
    // {
    //     $authorizationHeader = $request->getHeader('Authorization');
    //     if (empty($authorizationHeader)) {
    //         throw new RuntimeException("Auth token is not present");
    //     }

    //     $authorizationHeader = (string) $authorizationHeader[0];
        
    //     if (strlen($authorizationHeader) < 10) {
    //         throw new RuntimeException("Invalid auth token");
    //     }

    //     $token = substr($authorizationHeader,7);
    //     $this->authenticationService->verifyAuthToken($token);
    // }


}

